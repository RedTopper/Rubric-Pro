<?php
namespace RubricPro\flow\user;

use PDO;
use RubricPro\ui\Json;
use RubricPro\ui\info\Status;
use RubricPro\structure\Key;

class User extends Json {

	#constants for lengths and such
	const TIME_DIFFERENCE_MAX = 15;
	const PASSWORD_MIN = 7;
	const PASSWORD_MAX = 60;

	#possible account types
	const TYPE_STUDENT = "student";
	const TYPE_TEACHER = "teacher";
	const TYPE_NOBODY = "nobody";

	#sql database
	private $db;

	#user data
	private $num;
	private $user;
	private $first;
	private $last;
	private $time;
	private $type = User::TYPE_NOBODY;

	public function __construct(PDO $db) {
		parent::__construct();
		$this->db = $db;
	}

	public function fromLogin($username, $password) {
		$row = $this->getUser($username);

		#If the database says "CHANGE", then the user needs to set their password before they log in.
		if($row["PASSWORD"] === "CHANGE") {
			new Login(Login::CHANGE_FIRST_TIME(),
				"You need to change your password before you can log in.", "Please do that.", $row["USERNAME"]);
			return false;
		}

		#Verify password
		if(!(password_verify($password, $row["PASSWORD"]))) {
			new Login(Login::ERROR_USERNAME_PASSWORD_WRONG(),
				"Your username or password is incorrect.", "Try again.", $row["USERNAME"]);
			return false;
		}

		#logged in
		$this->num = $row["NUM"];
		$this->first = $row["FIRST_NAME"];
		$this->last = $row["LAST_NAME"];
		$this->time = time();

		#save data
		session_start();
		$this->save();

		new Login(Login::SUCCESS_LOGIN(),
			"You are now logged in!", "Enjoy!", $row["USERNAME"]);
		return true;
	}

	public function fromPasswordChange($username, $password, $passwordRetype) {
		$row = $this->getUser($username);

		if($row["PASSWORD"] !== "CHANGE") {
			new Login(Login::ERROR_NO_CHANGE(),
				"You do not need to change your password! Nice try.", "Please return to the login page.", $row["USERNAME"]);
			return false;
		}

		#Password length can't be too short
		if(strlen($password) < User::PASSWORD_MIN) {
			new Login(Login::ERROR_PASSWORD_SHORT(),
				"Your password needs to be at least " . User::PASSWORD_MIN . " characters!", "Choose a longer password!", $row["USERNAME"]);
			return false;
		}

		#Password length can't be too long. If it is, BCRYPT does some really weird stuff.
		if(strlen($password) > User::PASSWORD_MAX) {
			new Login(Login::ERROR_PASSWORD_LONG(),
				"Your password needs to be less than " . User::PASSWORD_MAX . " characters!", "Choose a shorter password!", $row["USERNAME"]);
			return false;
		}

		#Check if the passwords match
		if($password !== $passwordRetype) {
			new Login(Login::ERROR_PASSWORD_CHANGE_MISMATCH(),
				"The password retype field is different than the first password!", "Try again.", $row["USERNAME"]);
			return false;
		}

		#High cost in case passwords are stolen. Unlikely? Whatever.
		$options = ['cost' => 12];
		$stmt = null;

		#update password in database
		if($this->type === User::TYPE_TEACHER) {
			$stmt = $this->db->prepare("UPDATE TEACHER SET PASSWORD = :password WHERE NUM = :num");
		} else if($this->type === User::TYPE_STUDENT){
			$stmt = $this->db->prepare("UPDATE STUDENT SET PASSWORD = :password WHERE NUM = :num");
		} else {
			new Login(Login::ERROR_SERVER(),
				"Something bad happened. Action prevented.", "Try again.", $row["USERNAME"]);
			return false;
		}

		$hashword = password_hash($password, PASSWORD_BCRYPT, $options);
		$stmt->execute(['password' => $hashword, 'num' => $row["NUM"]]);
		new Login(Login::SUCCESS_CHANGED(),
			"Your password was changed!", "You can now log in.", $row["USERNAME"]);
		return true;
	}

	public function fromSession() {

		#recall session
		session_start();
		$this->fetch();

		#check if the user is timed out
		if($this->time + User::TIME_DIFFERENCE_MAX < time()) {
			new Status(Status::ERROR_TIMEOUT(),"Your session timed out.","Please return to the login page.");
			return false;
		}

		#update time per request
		$this->time = time();
		$this->save();

		#check if the user still exists
		if($this->getUser($this->user) === null) {
			new Status(Status::ERROR_AUTH(),"Your account was deleted.","Sorry about that :(");
			return false;
		}

		return true;
	}

	private function getUser($user) {
		#check from teachers table
		$stmt = $this->db->prepare("SELECT NUM, USERNAME, PASSWORD, FIRST_NAME, LAST_NAME FROM TEACHER WHERE USERNAME = :username");
		$stmt->execute(['username' => $user]);
		$row = $stmt->fetch();
		if($stmt->rowCount() === 1) {
			$this->user = $row["USERNAME"];
			$this->type = User::TYPE_TEACHER;
			return $row;
		}

		#check from students table
		$stmt = $this->db->prepare("SELECT NUM, USERNAME, PASSWORD, FIRST_NAME, LAST_NAME FROM STUDENT WHERE USERNAME = :username");
		$stmt->execute(['username' => $user]);
		$row = $stmt->fetch();
		if($stmt->rowCount() === 1) {
			$this->user = $row["USERNAME"];
			$this->type = User::TYPE_STUDENT;
			return $row;
		}

		new Login(Login::ERROR_USERNAME_PASSWORD_WRONG(),
			"Your username or password is incorrect.", "Try again.", $row["USERNAME"]);
		$this->type = User::TYPE_NOBODY;
		return null;
	}

	public function logout() {
		session_start();
		$this->fetch();
		$user = $this->user;
		$this->terminate();
		new Login(Login::SUCCESS_LOGOUT(),
			"You have been logged out!", "You can now log in.", $user);
	}

	private function fetch() {

		#check if session exists
		if(!isset($_SESSION[Key::NUM])) {
			new Status(Status::ERROR_AUTH(),"You are not logged in!","Please return to the login page.");
		}

		#recall session. Needs to be started prior to calling method!
		$this->num = $_SESSION[Key::NUM];
		$this->user = $_SESSION[Key::USER];
		$this->first = $_SESSION[Key::FIRST];
		$this->last = $_SESSION[Key::LAST];
		$this->time = intval($_SESSION[Key::TIME]);
	}

	private function save() {

		#Initialize session. Needs to be started prior to calling method!
		$_SESSION[Key::NUM] = $this->num;
		$_SESSION[Key::USER] = $this->user;
		$_SESSION[Key::FIRST] = $this->first;
		$_SESSION[Key::LAST] = $this->last;
		$_SESSION[Key::TIME] = $this->time;
	}

	private function terminate() {

		#Erases data and destroys the session. It's like the user was never here!
		$this->num = 0;
		$this->user = "";
		$this->first = "";
		$this->last = "";
		$this->time = 0;
		$this->save();
		session_destroy();
	}

	protected function compile() {
		$this->addJson(Key::USER, $this->user);
		$this->addJson(Key::NUM, $this->num);
		$this->addJson(Key::FIRST, $this->first);
		$this->addJson(Key::LAST, $this->last);
		$this->addJson(Key::ACCOUNT, $this->type);
		$this->addJson(Key::TIME, $this->time);
	}
}