<?php

Class Email{

	public $to = array();

	protected $environVars = null;

	public function __construct()
	{
		$this->environVars = $this->getEnvironmentVars();
	}



	private function getEnvironmentVars()
	{
		$_environment = array(
			"HTTP_HOST" => $_SESSION[HTTP_HOST],
			"REMOTE_ADDR" => $_SESSION[REMOTE_ADDR],
			"HTTP_USER_AGENT" => $_SESSION[HTTP_USER_AGENT],
			"HTTP_REFERER" => $_SESSION[HTTP_REFERER],
			"SERVER_NAME" => $_SESSION[SERVER_NAME],
			"SERVER_ADDR" => $_SESSION[SERVER_ADDR],
			"QUERY_STRING" => $_SESSION[QUERY_STRING],
			"REQUEST_URI" => $_SESSION[REQUEST_URI],
			"HTTP_COOKIE" => $_SESSION[HTTP_COOKIE],
			"COOKIE" => implode("<br>\n", $_COOKIE),
		);
		$tblEnviron = "
		<table>\n";
		foreach ($_environment as $key => $value) {

			$tblEnviron .= "
			<tr>
				<td style='padding:12px'>
					<b>$key</b>
				</td>
				<td>
					$value
				</td>
			</tr>\n";
		}
		return $tblEnviron .= "
		</table>\n";
	}

	public function send($to, $subject, $message)
	{

	}
}
?>
