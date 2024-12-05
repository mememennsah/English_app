<?php

class Controller_Test extends Controller
{
	
	public function action_index()
	{
		return Response::forge(View::forge('welcome/index'));
	}

	
}
