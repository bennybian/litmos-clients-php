<?php

namespace Litmos;

class Courses
{

    /**
     * @var Service
     */
    private $service;
	
	

    /**
     * @param Service $litmos_service
     */
    public function __construct(Service $litmos_service)
    {
        $this->service = $litmos_service;
    }

    /**
     * @param PagingSearch $ps
     *
     * @return Course[]
	 
	 Example:   [2] =>
	  Array
        (
            [Id] => jVAm87NAzjE1
            [Code] => CCCC001
            [Name] => Test Course
            [Active] => true
            [ForSale] => false
            [OriginalId] => 199342
            [Description] => This is testing putting in a course description
            [EcommerceShortDescription] => 
            [EcommerceLongDescription] => 
            [CourseCodeForBulkImport] => 199342-CCCC001
            [Price] => 0.00
            [AccessTillDate] => 
            [AccessTillDays] => 
        )
		
	 
     */
    public function getAll(PagingSearch $ps = null)
    {
        $response = $this->service->get('/courses', $ps);
       //var_dump($response);
        $xml = new \SimpleXMLElement($response);
		
		$course_nodes = $xml->children();
		foreach ($course_nodes as $element) {
		  $oneCourse=array();
		  foreach($element as $key => $val) {
		   $oneCourse[$key] =(string)$val;
		  }
		
		  $courses[]=$oneCourse;
		}
		
      /*  $courses      = array();
        $course_nodes = $xml->children();

        foreach ($course_nodes as $course_node) {
            $id     = (string)$course_node->Id;
            $code   = (string)$course_node->Code;
            $name   = (string)$course_node->Name;
            $active = filter_var((string)$course_node->Active, FILTER_VALIDATE_BOOLEAN);

            $course    = new Course($id, $code, $name, $active);
            $courses[] = $course;
        }*/

        return $courses;
    }
}
