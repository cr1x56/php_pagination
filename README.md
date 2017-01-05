# php_pagination
PHP pagination built on mysqli (PHP 5)

This class uses a mysqli_result and limits the results to the pagesize specified.


It also generates "First", "Next", "Previous" and "Last" properties to enable navigation



Simply include the pagination class

``` php
<?php include "path_to/pagination.php"; ?>
```


Then create a pagination class instance using any mysqli result

``` php
...
//get mysqli result
$mysqli_result = $mysqli_conn->query($sql);

//setup pagination object
$pagination = new Pagination($mysqli_result, $_GET, $url, $pageSize);
```


Results are returned using the 'get_results()' method.
All properties within this array can be accessed as if a regular mysqli_result were to be used (fetch_array(MYSQLI_ASSOC))
``` php
$paginated_array = $pagination->get_results();
```

###Parameters for constructor
$mysqlObj = Mysqli_result

$getObj = the $_GET object passed to class to retrieve current page and other parameters

$rawUrl = the url required to produce the navigation properties

$pageSize = The number of records to be displayed at any time. Default is 15

###Public methods
get_results() -> Produces an array limited by page size

CurrentPage() -> Gets the current page within the result set

TotalPages() -> Gets the total number of pages available

FirstPage() -> Returns the first page in the format "url?p=[first_page]&[any_other_params]"

LastPage() -> Returns the first page in the format "url?p=[last_page]&[any_other_params]"

NextPage() -> Returns the first page in the format "url?p=[current_page+1<=last_page]&[any_other_params]"

PrevPage() -> Returns the first page in the format "url?p=[current_page-1>0]&[any_other_params]"

hasNextPage() -> Returns a boolean indicating if there is a next page from the current page

hasPrevPage() -> Returns a boolean indicating if there is a previous page from the current page
