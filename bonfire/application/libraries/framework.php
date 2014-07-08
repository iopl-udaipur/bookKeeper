<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------

/**
 * Form Class
 *
 * @package    Bonfire
 * @subpackage Libraries
 * @category   Libraries
 * @author     Rajesh kakawat
 * @link       http://guides.cibonfire.com/core/unit_test.html
 * @version    3.0
 *
 */
class Framework
{

	/**
	 * Stores the CodeIgniter core object.
	 *
	 * @access private
	 * @static
	 *
	 * @var object
	 */
	private static $ci;

	//--------------------------------------------------------------------

	/**
	 * Constructor calls the init method
	 *
	 * @access public
	 * @uses   init()
	 *
	 * @return void
	 */
	public function __construct()
	{
		self::init();

	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Retrieves the CodeIgniter core object
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function init()
	{
		self::$ci =& get_instance();

	}//end init()


	public static function due_book_list()
	{
		self::$ci->load->model('books/books_model', null, true);
		return  self::$ci->books_model->due_book_list();
	}

	public static function due_student_list()
	{
		//self::$ci->model();
		return array();
	}

	public static function total_no_book()
	{
		self::$ci->load->model('books/books_model', null, true);
		return  self::$ci->books_model->get_total_no_of_book();
	}

	public static function total_no_student()
	{
		self::$ci->load->model('student/student_model', null, true);
		return  self::$ci->student_model->get_total_no_of_student();
	}

	public static function defaulter_student_list()
	{
		self::$ci->load->model('issue_book/issue_book_model', null, true);
		return  self::$ci->issue_book_model->get_defaulter_student();
	}

	public static function today_book_checkin_list()
	{
		// asrar
		// get the list of book that will be submitted current date.
		return  0;
	}

}//end class