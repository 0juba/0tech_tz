<?php

namespace Verifier;

class EmailExistenceVerifier implements VerifierInterface
{
    public function verify($email)
    {
        // TODO:
        // 1.   Стучимся на 25й порт на адрес указанный в MX записи домена, далее шлем HELO, потом RCPT - если получаем
        //      ответ 250 - то можно предположить что адрес существует - но это не надежный способ, так как сервер
        //      может отвечать ОК в любых случаях
        // 2.   Либо использовать сторонний сервис и отправлять на проверку ему email
    }
}
