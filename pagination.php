<?php
class Pagination {
	// Properties
	private $pageIndex;
	private $pageSize;
	private $totalPages;
	private $param;
	private $rawUrl;
	
	private $currentPage;
	private $returnObj;
	private $pages;
	
	//Public Methods
	public function get_results(){
		return $this->returnObj;
	}
	
	public function CurrentPage(){
		return $this->currentPage;
	}
	
	public function TotalPages(){
		return $this->totalPages;
	}
	
	public function FirstPage(){
		return $this->pages["first"];
	}
	
	public function LastPage(){
		return $this->pages["last"];
	}
	
	public function NextPage(){
		return $this->pages["next"];
	}
	
	public function PrevPage(){
		return $this->pages["previous"];
	}
	
	public function hasNextPage(){
		return $this->pages["hasNext"];
	}
	
	public function hasPrevPage(){
		return $this->pages["hasPrevious"];
	}
	
	//Constructor
	function __construct($mysqlObj, $getObj, $rawUrl, $pageSize = 15){
		$this->pageSize = $pageSize;
		$this->rawUrl = $rawUrl;
		$this->pageIndex = isset($getObj["p"]) ? $getObj["p"] : 1;
		$this->param = $this->getParams($getObj);
		$this->totalPages = ceil($mysqlObj->num_rows / $this->pageSize);
		
		//calculate other params
		$this->currentPage = $this->pageIndex > $this->totalPages ? $this->totalPages : $this->pageIndex;
		$this->returnObj = $this->limit($mysqlObj);
		$this->pages = $this->setupPages();
	}
	
	//Private Methods
	//setup pages in page array
	private function setupPages(){
		//setup page array
		$pageArr = [];
		
		//get param string
		$paramString = $this->buildUrlParams();
		
		//setup next page
		if(($this->currentPage + 1) > $this->totalPages) {
			$next = $this->totalPages;
			$isLast = true;
		}
		else {
			$next = $this->currentPage + 1;
			$isLast = false;
		}
		
		if(($this->currentPage - 1) < 1) {
			$prev = 1;
			$isFirst = true;
		}
		else {
			$prev = $this->currentPage - 1;
			$isFirst = false;
		}
		
		$pageArr["first"] = $this->rawUrl . "?p=1" . $paramString;
		$pageArr["last"] = $this->rawUrl . "?p=" . $this->totalPages . $paramString;
		$pageArr["next"] = $this->rawUrl . "?p=" . $next  . $paramString;
		$pageArr["previous"] = $this->rawUrl . "?p=" . $prev  . $paramString;
		$pageArr["hasNext"] = !$isLast;
		$pageArr["hasPrevious"] = !$isFirst;
		
		return $pageArr;
	}
	
	//gets all except p (p = page index)
	private function getParams($obj){
		if(isset($obj["p"])){
			//remove p from $obj
			array_splice($obj, array_search("p", array_keys($obj)), 1);
		}
		
		//return obj
		return $obj;
	}
	
	//builds the url parameter based on the param object
	private function buildUrlParams(){
		//setup param string
		$paramString = '';
		
		//only if param has items then build string
		foreach($this->param as $k=>$v){
			$paramString .= '&' . $k . '=' . $v;
		}
		
		//return param string
		return $paramString;
	}
	
	private function limit($mysqlObj){
		//get offset to start retrieving results
		$offset = ($this->pageIndex * $this->pageSize) - $this->pageSize;
		$limit = $this->pageSize;
		
		$fetchResults = [];
		//fetch all
		while($row = $mysqlObj->fetch_array(MYSQLI_ASSOC)){
			array_push($fetchResults, $row);
		}
		
		//setup resultSet
		$resultSet = array_slice($fetchResults, $offset, $limit);
		
		//intialize resultObj and return array
		$resultObj;
		$returnArray = [];
		
		//loop over result and setup resultset as objects within array
		foreach ($resultSet as $result)
		{
			//initialize object as new class
			$resultObj = new stdClass();
			
			foreach($result as $k=>$v){
				$resultObj->$k = $v;
			}
			
			//add object to array
			array_push($returnArray, $resultObj);
		}
		
		//return array
		return $returnArray;
	}
}
?>