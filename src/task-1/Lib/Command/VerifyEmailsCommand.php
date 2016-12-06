<?php

namespace Lib\Command;

use Lib\Logger\LoggerInterface;
use Lib\Verifier\Exception\DomainNotFoundException;
use Lib\Verifier\VerifierInterface;
use Lib\Verifier\Exception\EmailNotFoundException;
use Lib\Verifier\Exception\InvalidEmailException;

class VerifyEmailsCommand implements CommandInterface
{
    /**
     * @var VerifierInterface
     */
    private $verifier;
    /**
     * @var \PDO
     */
    private $connection;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var int
     */
    private $batchSize;

    public function __construct(
        VerifierInterface $verifier,
        \PDO $connection,
        LoggerInterface $logger,
        $batchSize
    )
    {
        $this->verifier = $verifier;
        $this->connection = $connection;
        $this->logger = $logger;
        $this->batchSize = $batchSize;
    }

    public function execute($startId)
    {
        if (!is_numeric($startId)) {
            throw new \InvalidArgumentException('Invalid ID passed.');
        }

        $query = $this->prepareStatement($startId);
        $query->execute();

        if ('00000' === $query->errorCode()) {
            $errorInfo = $query->errorInfo();

            throw new \Exception(empty($errorInfo[2]) ? 'Unknown DB error.' : $errorInfo[2]);
        }

        foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            try {
                $this->verifier->verify($row['email']);
            } catch (EmailNotFoundException $e) {
                $this->logger->log('[EMAIL DOES NOT EXIST]: ' . $e->getMessage());
            } catch (InvalidEmailException $e) {
                $this->logger->log('[EMAIL IS NOT VALID]: ' . $e->getMessage());
            } catch (DomainNotFoundException $e) {
                $this->logger->log('[EMAIL DOMAIN NOT FOUND]: ' . $e->getMessage());
            } catch (\Exception $e) {
                // NOP or some other logic
            }
        }

        if (!empty($row['id'])) {
            return $row['id'];
        }

        return null;
    }

    private function getBatchSize()
    {
        return $this->batchSize;
    }

    /**
     * @return \PDOStatement
     */
    private function prepareStatement($startId)
    {
        $st = $this->connection->prepare($this->getSQL());

        $st->bindValue(':email_id', $startId, \PDO::PARAM_INT);
        $st->bindValue(':batch_size', $this->getBatchSize(), \PDO::PARAM_INT);

        return $st;
    }

    private function getSQL()
    {
        return 'SELECT e.id, e.email FROM email e WHERE e.id > :email_id  ORDER BY e.id LIMIT :batch_size';
        /* Other way
         * SELECT e.id, e.email
         * FROM (
         *      SELECT e.id, e.email FROM email e LIMIT :batch_size, :offset
         * ) e
         * JOIN email j ON j.id=e.id
         * ORDER BY j.id
         */
    }
}
