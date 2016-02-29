<?php

class Auth
{

	public static function hash($password)
	{
		return password_hash($password, PASSWORD_BCRYPT);
	}

	public static function checkPassword($password, $hashedPassword)
	{
		return password_verify($password, $hashedPassword);
	}

	public static function checkLoggedIn() 
	{
		require_once './eagle/models/User.php';
		if (isset($_SESSION['email']) && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'])
		{
			if (User::where('email', $_SESSION['email']))
			{
				return true;
			}
		}
		self::logout();
		return false;
	}

	public static function login($email, $password)
	{
		require_once './eagle/models/User.php';
		$user = User::where('email', $email)->first();
		if ($user && self::checkPassword($password, $user->password))
		{
			$_SESSION['loggedIn'] = true;
			$_SESSION['email'] = $user->email;
			return true;
		}
		else 
		{
			return false;
		}
	}

	public static function logout()
	{
		unset($_SESSION['loggedIn']);
		unset($_SESSION['email']);
		return true;
	}

	public static function getLoggedInUser()
	{
		if (self::checkLoggedIn())
		{
			require_once './eagle/models/User.php';
			return User::where('email', $_SESSION['email'])->first();
		}
		return false;
	}

	public static function redirectIfNotLoggedIn()
	{
		if (!self::checkLoggedIn())
		{
			header('Location:/');
			exit();
		}
	}

}