<?php

namespace Lib\Service;

use Lib\Command\CommandInterface;

class EmailVerifierService
{
    /**
     * @var int
     */
    private $batchSize;
    /**
     * @var \PDO
     */
    private $connection;
    /**
     * @var CommandInterface
     */
    private $verifierCommand;

    public function __construct(\PDO $connection, CommandInterface $verifierCommand, $batchSize)
    {
        $this->batchSize = $batchSize;
        $this->connection = $connection;
        $this->verifierCommand = $verifierCommand;
    }

    public function verifyAll()
    {
        $emailIdBoundary = $this->getEmailIdBoundary();
        list ($cursor, $limit) = $emailIdBoundary;

        while ($cursor && $cursor <= $limit) {
            $this->verifierCommand->execute($cursor);
            $cursor += $this->batchSize;
        }
    }

    private function getEmailIdBoundary()
    {
        $st = $this->connection->query('SELECT MIN(id) AS min_id, MAX(id) AS max_id FROM email');
        $st->execute();

        $result = (array)$st->fetch();
        $defaults = array(null, null);

        return  $result + $defaults;
    }
}
