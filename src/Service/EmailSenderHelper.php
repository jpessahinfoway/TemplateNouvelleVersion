<?php


namespace App\Service;


use Exception;
use Swift_Message;
use Swift_TransportException;

class EmailSenderHelper
{


	private $m_mailer;


	public function __construct(\Swift_Mailer $mailer)
	{
		$this->m_mailer = $mailer;
	}


	/**
	 * Send email
	 *
	 * @param \Swift_Mailer $mailer
	 * @param string $from
	 * @param string $to
	 * @param string $replyTo
	 * @param string $subject
	 * @param $body
	 * @param string $bodyContentType
	 * @return bool
	 * @throws Exception
	 */
	public function sendEmail(string $from, string $to, string $replyTo, string $subject, $body, string $bodyContentType)
	{
		try
		{

			$mail = (new Swift_Message($subject))
				->setFrom($from)
				->setTo($to)
				->setReplyTo($replyTo)
				->setBody($body, $bodyContentType);

			$this->m_mailer->send($mail);

			return true;

		}
		catch (Swift_TransportException $e)
		{
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}

	}


}