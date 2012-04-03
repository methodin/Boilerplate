<?php
namespace Controllers;
class General
{
	public function index()
	{
		return array(
			'_template' => 'general/index.html',
			'test'		=> 'Hello World!'
		);
	}
}
