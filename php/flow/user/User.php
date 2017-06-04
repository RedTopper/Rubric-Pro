<?php
namespace RubricPro\flow\user;

use \PDO;
use RubricPro\ui\Json;
use ui\info\Status;

class User extends Json {

	#constants for lengths and such
	const TIME_DIFFERENCE_MAX = 15 * 60;
	const PASSWORD_MIN = 7;
	const PASSWORD_MAX = 60;

	#possible account types
	const TYPE_STUDENT = "student";
	const TYPE_TEACHER = "teacher";
	const TYPE_NOBODY = "nobody";

	#Keys for session storage
	const KEY_NUM = "num";
	const KEY_USER = "username";
	const KEY_FIRST = "first_name";
	const KEY_LAST = "last_name";
	const KEY_TYPE = "account_type";
	const KEY_TIME = "last_active_time";
	const KEY_MESSAGE = "message";
	const KEY_MESSAGE_TYPE = "message_type";

	#List of success messages
	const MSG_TYPE_SUCCESS = "success";
	const MSG_CHANGED = "Your password has been changed!";
	const MSG_SUCCESS = "Login successful!";

	#List of messages that require a password change
	const MSG_TYPE_CHANGE = "change";
	const MSG_REQUEST_CHANGE = "Your password must be set before you can log in for the first time!";
	const MSG_REQUIRE_CHANGE = "Your are required you to change your password!";

	#List of failure messages
	const MSG_TYPE_ERROR = "error";
	const MSG_MATCHING = "Passwords do not match. Please re-type your passwords.";
	const MSG_INCORRECT = "Username and password combination not found or the password is incorrect!";
	const MSG_SHORT = "Your password needs to be at least " . User::PASSWORD_MIN . " characters.";
	const MSG_LONG = "Your password needs to be less than " . User::PASSWORD_MAX . " characters.";
	const MSG_EXISTS = "That account already exists!";

	#sql database
	private $db;

	#user data
	private $num;
	private $user;
	private $first;
	private $last;
	private $type = User::TYPE_NOBODY;
	private $time;
	private $message;
	private $messageType;

	public function __construct(PDO $db) {
		parent::__construct();
		$this->db = $db;
	}

	public function fromLogin($username, $password) {
		$row = $this->getUser($username);
		if($row === null) {
			$this->message = User::MSG_INCORRECT;
			$this->messageType = User::MSG_TYPE_ERROR;
			return false;
		}

		#If the database says "CHANGE", then the user needs to set their password before they log in.
		if($row["PASSWORD"] === "CHANGE") {
			$this->message = User::MSG_REQUEST_CHANGE;
			$this->messageType = User::MSG_TYPE_SUCCESS;
			return false;
		}

		#Verify password
		if(!(password_verify($password, $row["PASSWORD"]))) {
			$this->message = User::MSG_INCORRECT;
			$this->messageType = User::MSG_TYPE_ERROR;
			return false;
		}

		#logged in
		$this->message = User::MSG_SUCCESS;
		$this->messageType = User::MSG_TYPE_SUCCESS;

		#Initialize Session
		session_start();
		$_SESSION[User::KEY_NUM] = $row["NUM"];
		$_SESSION[User::KEY_USER] = $row["USERNAME"];
		$_SESSION[User::KEY_FIRST] = $row["FIRST_NAME"];
		$_SESSION[User::KEY_LAST] = $row["LAST_NAME"];
		$_SESSION[User::KEY_MESSAGE] = $this->message;
		$_SESSION[User::KEY_MESSAGE_TYPE] = $this->messageType;
		$_SESSION[User::KEY_TIME] = time();
		return true;
	}

	public function fromPasswordChange($username, $password, $passwordRetype) {
		$row = $this->getUser($username);

		#Password length can't be too short
		if(strlen($password) < User::PASSWORD_MIN) {
			$this->message = User::MSG_SHORT;
			$this->messageType = User::MSG_TYPE_ERROR;
			return false;
		}

		#Password length can't be too long. If it is, BCRYPT does some really weird stuff.
		if(strlen($password) > User::PASSWORD_MAX) {
			$this->message = User::MSG_LONG;
			$this->messageType = User::MSG_TYPE_ERROR;
			return false;
		}

		#Check if the passwords match
		if($password !== $passwordRetype) {
			$this->message = User::MSG_MATCHING;
			$this->messageType = User::MSG_TYPE_ERROR;
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
			$this->message = User::MSG_INCORRECT;
			$this->messageType = User::MSG_TYPE_ERROR;
			return false;
		}

		$hashword = password_hash($password, PASSWORD_BCRYPT, $options);
		$stmt->execute(['password' => $hashword, 'num' => $row[User::KEY_NUM]]);
		$this->message = User::MSG_CHANGED;
		$this->messageType = User::MSG_TYPE_SUCCESS;
		return true;
	}

	public function fromSession() {
		session_start();
		$this->num = $_SESSION[User::KEY_NUM];
		$this->user = $_SESSION[User::KEY_USER];
		$this->first = $_SESSION[User::KEY_FIRST];
		$this->last = $_SESSION[User::KEY_LAST];
		$this->message = $_SESSION[User::KEY_MESSAGE];
		$this->messageType = $_SESSION[User::KEY_MESSAGE_TYPE];
		$this->time = intval($_SESSION[User::KEY_TIME]);

		#if the stored message is not successful, it's probably a problem
		if($this->messageType !== User::MSG_TYPE_SUCCESS) {
			new Status("","","");
			return false;
		}

		#check if the user is timed out
		if($this->time > time() + User::TIME_DIFFERENCE_MAX) {
			new Status("","","");
			return false;
		}

		#update time per request
		$this->time = time();

		#check if the user still exists
		if($this->getUser($this->user) === null) {
			new Status("","","");
			return false;
		}

		return true;
	}

	public function terminateSession() {
		session_start();
		$this->num = 0;
		$this->user = "";
		$this->first = "";
		$this->last = "";
		$this->message = "";
		$this->messageType = "";
		$this->time = 0;
		session_destroy();
	}

	private function getUser($user) {
		$stmt = $this->db->prepare("SELECT NUM, USERNAME, PASSWORD, FIRST_NAME, LAST_NAME FROM TEACHER WHERE USERNAME = :username");
		$stmt->execute(['username' => $user]);
		$row = $stmt->fetch();
		if($stmt->rowCount() === 1) {
			$this->type = User::TYPE_TEACHER;
			return $row;
		}

		$stmt = $this->db->prepare("SELECT NUM, USERNAME, PASSWORD, FIRST_NAME, LAST_NAME FROM STUDENT WHERE USERNAME = :username");
		$stmt->execute(['username' => $user]);
		$row = $stmt->fetch();
		if($stmt->rowCount() === 1) {
			$this->type = User::TYPE_STUDENT;
			return $row;
		}

		$this->type = User::TYPE_NOBODY;
		return null;
	}

	protected function compile() {
		$this->addJson(User::KEY_USER, $this->user);
		$this->addJson(User::KEY_NUM, $this->num);
		$this->addJson(User::KEY_FIRST, $this->first);
		$this->addJson(User::KEY_LAST, $this->last);
		$this->addJson(User::KEY_TYPE, $this->type);
		$this->addJson(User::KEY_NUM, $this->time);
		$this->addJson(User::KEY_MESSAGE, $this->message);
		$this->addJson(User::KEY_MESSAGE_TYPE, $this->messageType);
	}
}