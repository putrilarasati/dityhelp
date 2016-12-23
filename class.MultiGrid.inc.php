<?php
error_reporting(E_ALL ^ E_NOTICE);

class Multigridcls
{
	private $results_per_page = 10;
	private $column_count = 0; // Num of columns
	private $row_count = 0; // Number of rows
	private $hide_header = false; // Header visibility
	private $hide_footer = false; // Footer visibility
	private $hide_order = false; // Show ordering option
	private $show_checkboxes = false; // Show checkboxes
	private $allow_filters = false; // Allow filters or not
	private $row_select = false; // Enable row selection
	private $create_button = false; // Show create button
	private $print_button = false; // Show create button
	private $email_button = false; // Show create button
	private $reset_button = false; // Show reset grid button
	private $show_row_number = false; // Show row numbers
	private $hide_page_list = false; // Hide page list
	private $page = 1; // Current page
	private $primary = ''; // Tables primary key column
	private $query; // SQL query
	private $hidden = array(); // Hidden columns
	private $header = array(); // Header titles
	private $type = array(); // Column types
	private $controls = array(); // Row controls, std or custom
	private $order = false; // Current order
	private $filter = false; // Current filter
	private $limit = false; // Current limit
	private $_db, $result; // Database related
	private $select_fields = ''; // Field used to select
	private $select_where = ''; // Where clause
	private $select_table = ''; // Table to read
	private $image_path = '/images'; // Path to images
	private $_clsnumber = '1'; // Path to images
	private $height = "";

	// Filename of required images
	public $img_edit = 'gear.png';
	public $img_delete = 'delete.png';
	public $img_create = 'create.png';
	public $img_print = 'print.png';
	public $img_email = 'email.png';
	public $img_reset = 'reset.png';


	// Configuration constants
	const CUSCTRL_TEXT = 1;
	const CUSCTRL_IMAGE = 2;
	const STDCTRL_EDIT = 3;
	const STDCTRL_DELETE = 4;
	const TYPE_DATE = 1;
	const TYPE_IMAGE = 2;
	const TYPE_ONCLICK = 3;
	const TYPE_ARRAY = 4;
	const TYPE_DOLLAR = 5;
	const TYPE_HREF = 6;
	const TYPE_CHECK = 7;
	const TYPE_PERCENT = 8;
	const TYPE_CUSTOM = 9;
	const TYPE_FUNCTION = 10;
	const ORDER_DESC = 'DESC';
	const ORDER_ASC = 'ASC';


	// Default text
	const TXT_RESET = 'Reset Table';
	const TXT_NORESULTS = 'Belum ada data';


	public function __construct(EyeMySQLAdap $_db, $_clsnumber,$image_path = '')
	{
		$this->_db = $_db;
		$this->_clsnumber = $_clsnumber;

		if (empty($image_path))
			$this->image_path = '';
		else
			$this->image_path = $image_path;
		
		$parampage = 'page'.$_clsnumber;
		$paramorder = 'order'.$_clsnumber;
		$paramfilter = 'filter'.$_clsnumber;

		$page =	isset($_GET[$parampage]) ? (int) $_GET[$parampage] : '';
		//(int) $_GET['pagexno2']; // Page number
		$order	= isset($_GET[$paramorder]) ? $_GET[$paramorder] : '';
		// '';//$_GET['orderxno2']; // Order clause
		$filter	= isset($_GET[$paramfilter]) ? $_GET[$paramfilter] : '';
		// '';//$_GET['filterxno2']; // Filter clause

		// Set the limit
		if (empty($page) or $page <= 0)
			$this->setLimit(0, $this->results_per_page);
		else
			$this->page = $page;

		// Set the order
		if ($order)
		{
			list($column, $order) = $this->parseInputCond($order);
			$this->setOrder($column, $order);
		}

		// Set the filter
		if ($filter)
		{
			list($column, $value) = $this->parseInputCond($filter);
			$this->setFilter($column, $value);
		}
	}

	public function hidePageSelectList($hide = true)
	{
		$this->hide_page_list = $hide;
	}

	public function setGridHeight($height = 0)
	{
		$this->height = $height;
	}

	
	public function allowFilters($allow = true)
	{
		$this->allow_filters = $allow;
	}


	public function hideOrder($hide = true)
	{
		$this->hide_order = $hide;
	}




	public function showCheckboxes($show = true)
	{
		$this->show_checkboxes = $show;
	}


	public function hideHeader($hide = true)
	{
		$this->hide_header = $hide;
	}


	public function hideFooter($hide = true)
	{
		$this->hide_footer = $hide;
	}


	public function showReset($text = self::TXT_RESET)
	{
		$this->reset_button = $text;
	}


	public function showRowNumber($show = true)
	{
		$this->show_row_number = $show;
	}


	public function setQuery($fields, $table, $primary = '', $where = '')
	{
		$this->primary = $primary;

		$this->select_fields = $fields;
		$this->select_table = $table;
		$this->select_where = $where;
	}


	private function setFilter($column, $value)
	{
		$this->filter = array('Column' => $column,
				'Value' => $value);
	}


	private function setOrder($column, $order = self::ORDER_DESC)
	{
		$order = ($order == self::ORDER_DESC)
		? self::ORDER_DESC
		: self::ORDER_ASC;

		$this->order = array('Column' => $column,
				'Order' => $order);
	}


	public function hideColumn($column)
	{
		$this->hidden[] = $column;
	}


	public function setColumnHeader($column, $header)
	{
		$this->header[$column] = $header;
	}


	public function setColumnType($column, $type, $criteria = '', $criteria_2 = '')
	{
		$this->type[$column] = array($type, $criteria, $criteria_2);
	}


	public function setResultsPerPage($num)
	{
		$this->results_per_page = (int) $num;
		$this->setLimit(0, (int) $num);
	}


	public function addStandardControl($type, $action, $action_type = self::TYPE_ONCLICK)
	{
		$action = $this->parseLinkAction($action, $action_type);

		switch ($type)
		{
			case self::STDCTRL_EDIT:
				$this->controls[] = '<a ' . $action . '>edit</a>';
				break;
			case self::STDCTRL_DELETE:
				$this->controls[] = '<a ' . $action . '>del</a>';
				break;
			default:
				// Invalid standard control
				break;
		}
	}


	public function addCustomControl($type = self::CUSCTRL_TEXT, $action, $action_type = self::TYPE_ONCLICK, $text, $image_src = '')
	{
		$action = $this->parseLinkAction($action, $action_type);

		switch ($type)
		{
			case self::CUSCTRL_IMAGE:
				$this->controls[] = '<a ' . $action . '>+</a>';
				break;
			default: // Default to text
				$this->controls[] = '<a ' . $action . '>' . $text . '</a>';
				break;
		}
	}


	public function showCreateButton($action, $action_type = self::TYPE_ONCLICK, $text = 'New Record')
	{
		$action = $this->parseLinkAction($action, $action_type);

		$this->create_button = array('Action' => $action,
				'Text' => $text);
	}

	public function showPrintButton($action, $action_type = self::TYPE_ONCLICK, $text = 'Print')
	{
		$action = $this->parseLinkAction($action, $action_type);

		$this->print_button = array('Action' => $action,
				'Text' => $text);
	}
	
	public function showEmailButton($action, $action_type = self::TYPE_ONCLICK, $text = 'Email')
	{
		$action = $this->parseLinkAction($action, $action_type);

		$this->email_button = array('Action' => $action,
				'Text' => $text);
	}

	public function addRowSelect($onclick)
	{
		$this->row_select = $onclick;
	}


	private function parseInputCond($in)
	{
		return explode(':', preg_replace("[#\'\"\<\>\\#]", '%', $in), 2);
	}


	private function parseVariables(array $row, $act)
	{
		// The only way we get an array for $act is for parameters from a column type of function
		if (is_array($act))
		{
			// Loop through each passed param and replace variables where necessary
			foreach ($act as $key => $value)
				$act[$key] = $this->parseVariables($row, $value);

			return $act;
		}

		// %_P% is an alias for the primary key, replace it with the primary key
		if ($this->primary)
			$act = str_replace('%_P%', '%' . $this->primary . '%', $act);

		preg_match_all("/%([A-Za-z0-9_ \-]*)%/", $act, $vars);

		foreach($vars[0] as $v)
			$act = str_replace($v, $row[str_replace('%', '', $v)], $act);

		return $act;
	}


	private function parseLinkAction($action, $action_type)
	{
		if ($action_type == self::TYPE_ONCLICK)
			$action = 'href="javascript:;" onclick="' . $action . '"';
		else
			$action = 'href="' . $action . '"';

		return $action;
	}


	private function setLimit($low, $high)
	{
		$this->limit = array('Low' => $low,
				'High' => $high);
	}


	public static function isAjaxUsed()
	{
		if (!empty($_GET['useajax']) and $_GET['useajax'] == 'true')
			return true;

		return false;
	}


	private function buildHeader()
	{
		// If entire header is hidden, skip all together
		if ($this->hide_header)
			return;

		//echo '<thead><tr>';
		echo '<thead><tr style="background-color: #4B8DF8;font-size: 12px;" role="row">';
	
		// Get field names of result
		$headers = $this->_db->fieldNameArray($this->result);
		$this->column_count = count($headers);

		// Add a blank column if the row number is to be shown
		if ($this->show_row_number)
		{
			$this->column_count++;
			//echo '<td class="table table-striped">&nbsp;</td>';
				echo '<th width="3%" style="background-color: #4B8DF8;font-size: 12px;"><font color="white">No.</font></th>';		
		}
		
		// Show checkboxes
		if ($this->show_checkboxes)
		{
			$this->column_count++;
			//echo '<td class="table table-striped tbl-checkall" ><input type="checkbox" name="checkall" onclick="tblToggleCheckAll'.$this->_clsnumber.'()"></td>';
			echo '<th class="table table-striped tbl-checkall" ><font color="white"><input type="checkbox" name="checkall" onclick="tblToggleCheckAll'.$this->_clsnumber.'()"></font></th>';			
		}

		// Loop through each header and output it
		foreach ($headers as $t)
		{
			// Skip column if hidden
			if (in_array($t, $this->hidden))
			{
				$this->column_count--;
				continue;
			}

			// Check for header caption overrides
			if (array_key_exists($t, $this->header))
				$header = $this->header[$t];
			else
				$header = $t;

			if ($this->hide_order)
				//echo '<td class="table table-striped">' . $header; // Prevent the user from changing order
				echo '<th class="center sorting_disabled" role="columnheader" rowspan="1" colspan="1" style="width: 0px;"><font color="white">' . $header;
			else {
				if ($this->order and $this->order['Column'] == $t)
					$order = ($this->order['Order'] == self::ORDER_ASC)
					? self::ORDER_DESC
					: self::ORDER_ASC;
				else
					$order = self::ORDER_ASC;

				echo '<th class="center sorting_disabled" role="columnheader" rowspan="1" colspan="1" style="width: 0px;">'
				.'<font color="white">'
				.'<a href="javascript:;" style="color:#FFFFFF" onclick="tblSetOrder'.$this->_clsnumber.'(\'' . $t . '\', \'' . $order . '\')">' 
				//.'<a href="javascript:;" style="color:#FFFFFF">' 
				. $header 
				. "</a>"
				;

				// Show the user the order image if set
				if ($this->order and $this->order['Column'] == $t){
					if(strtolower($this->order['Order']) == 'asc') {echo ' <i class="fa fa-sort-alpha-asc"></i>';}
					else if(strtolower($this->order['Order']) == 'desc') {echo ' <i class="fa fa-sort-alpha-desc"></i>';}
				}
				/*if ($this->order and $this->order['Column'] == $t)
					echo '&nbsp;<img src="images/sort_' . strtolower($this->order['Order']) . '.gif" class="tbl-order">';*/
			}

			// Add filters if allowed and only if the column type is not "special"
			if ($this->allow_filters and !empty($t)){
					
				if (!in_array($this->type[$t][0], array(
						self::TYPE_ARRAY,
						self::TYPE_IMAGE,
						self::TYPE_FUNCTION,
						self::TYPE_DATE,
						self::TYPE_CHECK,
						self::TYPE_CUSTOM,
						self::TYPE_PERCENT
				)))
				{
					if ($this->filter['Column'] == $t and !empty($this->filter['Value']))
					{
						$filter_display = 'block';
						$filter_value = $this->filter['Value'];
					} else {
						$filter_display = 'none';
						$filter_value = '';
					}
					echo '<a href="javascript:;" onclick="tblShowHideFilter'. $this->_clsnumber .'(\'' . $t . '\')"> filter </a>
					<br><div class="tbl-filter-box" id="'.$this->_clsnumber.'filter-' . $t . '" style="display:' . $filter_display . '">
					<input type="text" size="6" id="'.$this->_clsnumber.'filter-value-' . $t . '" value="'.$filter_value.'">&nbsp;
					<a href="javascript:;" onclick="tblSetFilter'.$this->_clsnumber.'(\'' . $t . '\')">filter</a></div>';
				}
					
			}

			echo '</font></th>';
		}

		// If we have controls, add a blank column
		if (count($this->controls) > 0)
		{
			$this->column_count++;
			echo '<th class="table table-striped" style="background-color: #4B8DF8;font-size: 12px;">&nbsp;</th>';
		}

		echo '</tr></thead>';
	}


	private function buildFooter($shown, $first = 0, $last = 0)
	{
		// Skip adding the footer if it is hidden
		if ($this->hide_footer)
			return;

		$pages = ceil($this->row_count / $this->results_per_page); // Total number of pages

		/*echo '<tr class="table table-striped"><td class="tbl-nav" colspan="' . $this->column_count . '">
		<table width="100%" ><tr><td width="33%" class="tbl-found">Ada <em>' . $this->row_count . '</em>';*/
		echo '<table width="100%" ><tr><td width="33%" class="tbl-found">Ada <em>' . $this->row_count . '</em>';
		
		if ($this->row_count > 0)
			echo ', No : <em>' . $first . '</em> sd <em>' . $last . '</em>';

		echo '</td><td width="33%" class="tbl-pages">';

		// Handle results that span multiple pages
		if ($this->row_count > $this->results_per_page)
		{
			if ($this->page > 1)
				echo '<a href="javascript:;" onclick="tblSetPage'.$this->_clsnumber.'(1)"><img src="images/arrow_first.gif" class="tbl-arrows" alt="<<" title="First Page">
				</a><a href="javascript:;" onclick="tblSetPage'.$this->_clsnumber.'(' . ($this->page - 1) . ')">
				<img src="images/arrow_left.gif" class="tbl-arrows" alt="<" title="Previous Page"></a>';
			else
				echo '<img src="images/arrow_first_disabled.gif" class="tbl-arrows" alt="<<" title="First Page">
				<img src="images/arrow_left_disabled.gif" class="tbl-arrows" alt="<" title="Previous Page">';
			if ($pages < 20) {
				for ($i = 1; $i <= $pages; $i++)
				{
				if ($i == $this->page)
					echo '&nbsp;<span class="page-selected">' . $i . '</span>&nbsp;';
          else
				echo '&nbsp;<a href="javascript:;" onclick="tblSetPage'.$this->_clsnumber.'(' . $i . ')">' . $i . '</a>&nbsp;';
				}
				}

			if ($this->page < $pages)
				echo '<a href="javascript:;" onclick="tblSetPage'.$this->_clsnumber.'(' . ($this->page + 1) . ')">
				<img src="images/arrow_right.gif" class="tbl-arrows" alt=">" title="Next Page"></a>
				<a href="javascript:;" onclick="tblSetPage'.$this->_clsnumber.'(' . $pages . ')">
				<img src="images/arrow_last.gif" class="tbl-arrows" alt=">>" title="Last Page"></a>';
			else
				echo '<img src="images/arrow_right_disabled.gif" class="tbl-arrows" alt=">" title="Next Page">
				<img src="images/arrow_last_disabled.gif" class="tbl-arrows" alt=">>" title="Last Page">';
		}

		echo '</td><td width="33%" class="tbl-page">';

		// Only show page section if we have more than one page
				if ($pages > 0)
				{
				echo 'Page ';
				if (!$this->hide_page_list and $pages > 1)
				{
				// Create a selectable drop down list for pages
					echo '<select name="tbl-page" onchange="tblSetPage'.$this->_clsnumber.'(this.options[this.selectedIndex].value)">';
				for ($x = 1; $x <= $pages; $x++)
					{
					echo '<option value="' . $x . '"';
					if ($x == $this->page)
					echo ' selected="selected"';
					echo '>' . $x . '</option>';
					}
					echo '</select>';
				} else
				echo $this->page; // Just write the page number, nothing to fancy

				echo ' of ' . $pages;
				}

						//echo '</td></tr></table></td></tr>';
						echo '</td></tr></table>';
	}


	private function buildControls(array $row)
	{
	// Add controls as needed
							if (count($this->controls) > 0)
							{
							echo '<td class="tbl-controls">';
									foreach ($this->controls as $ctl)
										echo $this->parseVariables($row, $ctl);
									echo '</td>';
}
}


public function printTable($table_custom,$url)
	{
	// Set the limit
	$this->setLimit(($this->page - 1) * $this->results_per_page, $this->results_per_page);

	// FILTER
	$filter = '';
	if ($this->select_where)
		$filter .= "WHERE " . $this->select_where . "";

	//if ($this->allow_filters and $this->filter)
	if ($this->filter)
	{
		if (!strstr($this->filter['Value'], '%'))
			$filter_value = '%' . $this->filter['Value'] . '%';
		else
			$filter_value = $this->filter['Value'];

		if (!$this->select_where)
			$filter .= "WHERE ";
		else
			$filter = $filter . " AND ";

		$filter .= "(" . $this->filter['Column'] . " LIKE '" . $filter_value . "')";
	}
	
	// ORDER
	if ($this->order)
		$order = "ORDER BY `" . $this->order['Column'] . "` " . $this->order['Order'];
	else
		$order = '';

	// LIMIT
	if ($this->limit)
		$limit = "LIMIT " . $this->limit['Low'] . ", " . $this->limit['High'];
	else
		$limit = '';

	$query = 'SELECT ' . $this->select_fields . ' FROM ' . $this->select_table . ' ' . $filter;

	// Inform the user of any errors. Commonly caused when a column is specified in the filter or order clause that does not exist
	$this->result = $this->_db->query($query . ' ' . $order . ' ' . $limit, false);
	if (!$this->result)
	{
		echo '<div style="color: red; font-weight: bold; border: 2px solid red; padding: 10px;">
			Oops! We ran into a problem while trying to output the table. <a href="javascript:;" onclick="tblReset'.$this->_clsnumber.'()">
			Click here</a> to reset the table or <a href="javascript:;" onclick="alert(\'' . preg_replace('[\'"]', '', $this->_db->error()) . '\')">here</a>
			to review the error.</div>';
		return;
	}

	// Count the number of rows without the limit clause
	$this->row_count = $this->_db->countRows($query);

	
	if (!$this->isAjaxUsed())
	{
		// Print out required javascript functions		
		$this->printJavascript($url);
		echo '<script type="text/javascript">function updateTable'.$this->_clsnumber.'() { window.location = "?" + params' .$this->_clsnumber .'; }</script>';
	}

	if ($this->height != ""){$grid_height = " style=\"height:" .$this->height ."px\""; }
	echo '<div' .$grid_height .' class="table-responsive"><form action="#" name="dg2" id="dg2">';

	// Output the create button
	if ($this->create_button)
		echo '<span class="tbl-create"><a ' . $this->create_button['Action'] . ' title="' . $this->create_button['Text'] . '">
			<img src="images/create.png"  alt="" class="tbl-create-image">' . $this->create_button['Text'] . '</a></span>';
	if ($this->print_button)
		echo '<span class="tbl-print"><a ' . $this->print_button['Action'] . ' title="' . $this->print_button['Text'] . '">
			<img src="images/print.png"  alt="" class="tbl-print-image">' . $this->print_button['Text'] . '</a></span>';
	if ($this->email_button)
		echo '<span class="tbl-email"><a ' . $this->email_button['Action'] . ' title="' . $this->email_button['Text'] . '">
			<img src="images/email.png"  alt="" class="tbl-email-image">' . $this->email_button['Text'] . '</a></span>';
	// Output the reset button
	if ($this->reset_button)
		echo '<span class="tbl-reset"><a href="javascript:;" onclick="tblReset'.$this->_clsnumber.'()" title="' . $this->reset_button .'">
			<img src="images/reset.png"  alt="" class="tbl-reset-image">' . $this->reset_button .'</a></span>';

	//echo '<table class="tbl">';
	if ($this->row_count == 0) $idtable_custom = '';
	else $idtable_custom = 'id="'.$table_custom.'"';
	echo '<table class="table table-striped dataTable" '.$idtable_custom.'>';
	
	$this->buildHeader();

	echo '<tbody>';

	if ($this->row_count == 0)
		echo '<tr><td colspan="' . $this->column_count . '" class="tbl-noresults">' . self::TXT_NORESULTS . '</td></tr>';
	else {
		$i = 0; $first = 0; $last = 0;

		while ($row = $this->_db->fetchAssoc($this->result))
		{

			 

			echo '<tr  class="tbl-row tbl-row-' . (($i % 2) ? 'odd' : 'even'); // Switch up the bgcolors on each row

			//			echo '<tr class="tbl-row tbl-row-' . (($i % 2) ? 'odd' : 'even'); // Switch up the bgcolors on each row

			// Handle row selects
			if ($this->row_select)
				echo ' tbl-row-highlight" onclick="' . $this->parseVariables($row, $this->row_select);

				echo '">';

				$line = ($this->page == 1)
				? $i + 1
				: $i + 1 + (($this->page - 1) * $this->results_per_page);

				$last = $line; // Last line
				if ($first == 0)
					$first = $line; // First line
				$style = '';
				if(isset($_REQUEST['idT'])){
					if($_REQUEST['idT'] == $row['ID']) $style = "color:#4B8DF8;font-size: 14px;font-weight: bold";
				}
					
				if ($this->show_row_number)
					echo '<td class="tbl-row-num" style="'.$style.'" >' . $line . '</td>';

				if ($this->show_checkboxes)
					echo '<td align="center"><input type="checkbox" class="tbl-checkbox" id="checkbox" name="tbl-checkbox" value="' . $row[$this->primary] . '"></td>';

				foreach ($row as $key => $value)
				{
					// Skip if column is hidden
					if (in_array($key, $this->hidden))
						continue;

					// Apply a column type to the value
					if (array_key_exists($key, $this->type))
					{
						list($type, $criteria, $criteria_2) = $this->type[$key];

						switch ($type)
						{
							case self::TYPE_ONCLICK:
								if ($value)
									$value = '<a href="javascript:;" onclick="' . $this->parseVariables($row, $criteria) . '">' . $value . '</a>';
									break;

							case self::TYPE_HREF:
								if ($value)
									$value = '<a href="' . $this->parseVariables($row, $criteria) . '">' . $value . '</a>';
									break;

							case self::TYPE_DATE:
								if ($value == "0000-00-00" || $value == "" || $value === NULL) {
									$value = "";
								}
								else {
									if ($criteria_2 == true)
										$value = date($criteria, strtotime($value));
										else
											$value = date($criteria, $value);
								}
								break;
							case self::TYPE_IMAGE:
								$value = '<img src="' . $this->parseVariables($row, $criteria) . '" id="' . $key . '-' . $i . '">';
								break;

							case self::TYPE_ARRAY:
								$value = $criteria[$value];
								break;

							case self::TYPE_CHECK:
								if ($value == '1' or $value == 'yes' or $value == 'true' or ($criteria != '' and $value == $criteria))
									$value = '<img src="images/check.gif">';
									break;

							case self::TYPE_PERCENT:
								if ($crtieria == true)
									$value *= 100; // Value is in decimal format

									$value = round($value); // Round to the nearest decimal

									$value .= '%';

									// Apply a bar if an array is supplied via criteria_2
									if (is_array($criteria_2))
										$value = '<div style="background: ' . $criteria_2['Back'] . '; width: ' . $value . '; color: ' . $criteria_2['Fore'] . ';">' . $value . '</div>';
									break;

							case self::TYPE_DOLLAR:
								$value = '$' . number_format($value, 2);
								break;

							case self::TYPE_CUSTOM:
								$value = $this->parseVariables($row, $criteria);
								break;

							case self::TYPE_FUNCTION:
								if (is_array($criteria_2))
									$value = call_user_func_array($criteria, $this->parseVariables($row, $criteria_2));
									else
										$value = call_user_func($criteria, $this->parseVariables($row, $criteria_2));
									break;

							default:
								// Invalid column type
								break;
						}
					}
					
					//$style = "color:#4B8DF8;font-size: 14px;font-weight: bold";
					echo '<td style="'.$style.'" class="tbl-cell">' . $value . '</td>';
				}

				$this->buildControls($row);
				
				echo '</tr>';

				$i++;
		}
	}

	echo '</tbody>';

	//$this->buildFooter($i, $first, $last);
	


	echo '</table></form></div>';
	if( (isset($i) && isset($first) && isset($last)) ) {
		$this->buildFooter($i, $first, $last);
	} else {
		$this->buildFooter(1, false, false);
	}
}

public static function useAjaxTable($responce = '', $_clsnumber)
{
	// If no responce script is set, use the current script
	if (empty($responce))
		$responce = $_SERVER['PHP_SELF'];
	echo "<script type=\"text/javascript\">\n";
	echo "var params" .$_clsnumber ." = ''; var tblpage" .$_clsnumber ." = '" . $page . "'; var tblorder" .$_clsnumber ." = '" . $order . "'; var tblfilter" .$_clsnumber ." = '" . $filter . "';\n";
	echo "function tblSetPage".$_clsnumber."(page) { tblpage" .$_clsnumber ." = page; params" .$_clsnumber ." = 'page".$_clsnumber."=' + page + '".$url."&order".$_clsnumber."=' + tblorder" .$_clsnumber ." + '&filter".$_clsnumber."=' + tblfilter" .$_clsnumber ."; updateTable".$_clsnumber."(); }\n";
	echo "function tblSetOrder".$_clsnumber."(column, order) { tblorder" .$_clsnumber ." = column + ':' + order; params" .$_clsnumber ." = '&page".$_clsnumber."=' + tblpage" .$_clsnumber ." + '".$url."&order".$_clsnumber."=' + tblorder" .$_clsnumber ." + '&filter".$_clsnumber."=' + tblfilter" .$_clsnumber ."; updateTable".$_clsnumber."(); }\n";
	echo "function tblSetFilter".$_clsnumber."(column) { val = document.getElementById('".$_clsnumber."filter-value-' + column).value; tblfilter" .$_clsnumber ." = column + ':' + val; tblpage" .$_clsnumber ." = 1; params" .$_clsnumber ." = 'page".$_clsnumber."=1&order".$_clsnumber."=' + tblorder" .$_clsnumber ." + '&filter".$_clsnumber."=' + tblfilter" .$_clsnumber ."; updateTable".$_clsnumber."(); }\n";
	echo "function tblClearFilter".$_clsnumber."() { tblfilter" .$_clsnumber ." = ''; params" .$_clsnumber ." = 'page".$_clsnumber."=1&order".$_clsnumber."=' + tblorder" .$_clsnumber ." + '&filter".$_clsnumber."='; updateTable".$_clsnumber."(); }\n";
	echo "function tblToggleCheckAll".$_clsnumber."() { for (i = 0; i < document.dg2.checkbox.length; i++) { document.dg2.checkbox[i].checked = !document.dg2.checkbox[i].checked; } }\n";
	echo "function tblShowHideFilter".$_clsnumber."(column) { var o = document.getElementById('".$_clsnumber."filter-' + column); if (o.style.display == 'block') { tblClearFilter".$_clsnumber."(); } else {	o.style.display = 'block'; } }\n";
	echo "function tblReset".$_clsnumber."() { params" .$_clsnumber ." = 'page".$_clsnumber."=1'; updateTable".$_clsnumber."(); }\n";
	echo "</script>\n";
	
	echo '<style type="text/css">
		body { }
		table.tbl { }
		td.tbl-header {}
		tr.tbl-footer {}
		table.tbl-footer { }
		tr.tbl-row {}
		tr.tbl-row:hover { background: #EBFFFF; }
		tr.tbl-row-even { background: #f4f4f4; }
		tr.tbl-row.selected { background: royalblue; color: #fff}
		tr.tbl-row-odd { background: white; }
		tr.tbl-row-highlight:hover { background: #fffba6; cursor: pointer; }
		td.tbl-nav { height: 20px; border-top: 2px solid #c3daf9; color: #4D4D4D; }
		td.tbl-pages { text-align: center; }
		td.tbl-row-num { text-align: right; }
		td.tbl-cell {}
		tr.tbl-row.selected > td.tbl-cell { background: royalblue}
		td.tbl-controls { text-align: center; }
		td.tbl-found {}
		td.tbl-checkall {}
		td.tbl-page { text-align: right; }
		td.tbl-noresults { font-weight: bold; color: #9F0000; height: 45px; text-align: center; }
		span.tbl-reset { margin: 5px 5px; }
		img.tbl-reset-image { margin-right: 5px; border: 0; }
		span.tbl-create { margin: 5px 0; }
		img.tbl-create-image { margin-right: 5px; border: 0; }
		div.tbl-filter-box {}
		img.tbl-arrows { border: 0; }
		img.tbl-order-image { margin: 0 2px; border: 0; }
		img.tbl-filter-image { border: 0; }
		img.tbl-control-image { border: 0; }
		span.page-selected { color: black; font-weight: bold; }
		input.tbl-checkbox {}
		</style>
		';
	// */
	
	echo "<script type=\"text/javascript\">\n";
	echo "var xmlHttp" .$_clsnumber ."\n";
	echo "function SetXmlHttpObject" .$_clsnumber ."() {\n";
	echo "xmlHttp" .$_clsnumber ."= null;\n";
	echo "try { xmlHttp" .$_clsnumber ." = new XMLHttpRequest(); }\n";
	echo "catch (e) {\n";
	echo "try { xmlHttp" .$_clsnumber ." = new ActiveXObject('Msxml2.XMLHTTP'); }\n";
	echo "catch (e) { xmlHttp" .$_clsnumber ." = new ActiveXObject('Microsoft.XMLHTTP'); } }\n";
	echo "if (xmlHttp" .$_clsnumber ." == null) {alert('Your web browser does not support Ajax'); }\n";
	echo "return xmlHttp" .$_clsnumber ."; }\n";
	echo "function stateChanged" .$_clsnumber ."(){ if (xmlHttp" .$_clsnumber .".readyState == 4) { document.getElementById('Multigrid" .$_clsnumber ."').innerHTML = xmlHttp" .$_clsnumber .".responseText; } }\n";
	echo "function updateTable" .$_clsnumber ."() { xmlHttp" .$_clsnumber ." = SetXmlHttpObject" .$_clsnumber ."(); xmlHttp" .$_clsnumber .".onreadystatechange = stateChanged" .$_clsnumber ."; xmlHttp" .$_clsnumber .".open('GET', '" .$responce . "&useajax=true&' + params" .$_clsnumber .", true); xmlHttp" .$_clsnumber .".send(null); }\n";
	echo "</script>\n";
	echo "<div id=\"Multigrid" .$_clsnumber ."\"></div>\n";
	echo "<script type=\"text/javascript\">updateTable" .$_clsnumber ."();</script>\n";
}

public function printJavascript($url)
{
	if ($this)
	{
		$page = $this->page;
		$order = (($this->order) ? implode(':', $this->order) : '');
		$filter = (($this->filter) ? implode(':', $this->filter) : '');
	}

	echo '<style type="text/css">
		body { }		
		table.tbl {  }
		td.tbl-header {}
		tr.tbl-footer {}
		table.tbl-footer { }
		tr.tbl-row {}
		tr.tbl-row.selected { background: royalblue;}
		tr.tbl-row:hover { background: #EBFFFF; }
		tr.tbl-row-even { background: #f4f4f4; }
		tr.tbl-row-odd { background: white; }
		tr.tbl-row-highlight:hover { background: #fffba6; cursor: pointer; }
		td.tbl-nav {}
		td.tbl-pages { text-align: center; }
		td.tbl-row-num { text-align: right; }
		td.tbl-cell {}
		td.tbl-controls { text-align: center; }
		td.tbl-found {}
		td.tbl-checkall {}
		td.tbl-page { text-align: right; }
		td.tbl-noresults { font-weight: bold; color: #9F0000; height: 45px; text-align: center; }
		span.tbl-reset { margin: 5px 5px; }
		img.tbl-reset-image { margin-right: 5px; border: 0; }
		span.tbl-create { margin: 5px 0; }
		img.tbl-create-image { margin-right: 5px; border: 0; }
		div.tbl-filter-box {}
		img.tbl-arrows { border: 0; }
		img.tbl-order-image { margin: 0 2px; border: 0; }
		img.tbl-filter-image { border: 0; }
		img.tbl-control-image { border: 0; }
		span.page-selected { color: black; font-weight: bold; }
		input.tbl-checkbox {}
		</style>
		';
// */		

	echo "<script type=\"text/javascript\">\n";
	echo "var params" .$this->_clsnumber ." = ''; var tblpage" .$this->_clsnumber ." = '" . $page . "'; var tblorder" .$this->_clsnumber ." = '" . $order . "'; var tblfilter" .$this->_clsnumber ." = '" . $filter . "';\n";
	echo "function tblSetPage".$this->_clsnumber."(page) { tblpage" .$this->_clsnumber ." = page; params" .$this->_clsnumber ." = 'page".$this->_clsnumber."=' + page + '".$url."&order".$this->_clsnumber."=' + tblorder" .$this->_clsnumber ." + '&filter".$this->_clsnumber."=' + tblfilter" .$this->_clsnumber ."; updateTable".$this->_clsnumber."(); }\n";
	echo "function tblSetOrder".$this->_clsnumber."(column, order) { tblorder" .$this->_clsnumber ." = column + ':' + order; params" .$this->_clsnumber ." = '&page".$this->_clsnumber."=' + tblpage" .$this->_clsnumber ." + '".$url."&order".$this->_clsnumber."=' + tblorder" .$this->_clsnumber ." + '&filter".$this->_clsnumber."=' + tblfilter" .$this->_clsnumber ."; updateTable".$this->_clsnumber."(); }\n";
	echo "function tblSetFilter".$this->_clsnumber."(column) { val = document.getElementById('".$this->_clsnumber."filter-value-' + column).value; tblfilter" .$this->_clsnumber ." = column + ':' + val; tblpage" .$this->_clsnumber ." = 1; params" .$this->_clsnumber ." = 'page".$this->_clsnumber."=1&order".$this->_clsnumber."=' + tblorder" .$this->_clsnumber ." + '&filter".$this->_clsnumber."=' + tblfilter" .$this->_clsnumber ."; updateTable".$this->_clsnumber."(); }\n";
	echo "function tblClearFilter".$this->_clsnumber."() { tblfilter" .$this->_clsnumber ." = ''; params" .$this->_clsnumber ." = 'page".$this->_clsnumber."=1&order".$this->_clsnumber."=' + tblorder" .$this->_clsnumber ." + '&filter".$this->_clsnumber."='; updateTable".$this->_clsnumber."(); }\n";
	echo "function tblToggleCheckAll".$this->_clsnumber."() { for (i = 0; i < document.dg2.checkbox.length; i++) { document.dg2.checkbox[i].checked = !document.dg2.checkbox[i].checked; } }\n";
	echo "function tblShowHideFilter".$this->_clsnumber."(column) { var o = document.getElementById('".$this->_clsnumber."filter-' + column); if (o.style.display == 'block') { tblClearFilter".$this->_clsnumber."(); } else {	o.style.display = 'block'; } }\n";
	echo "function tblReset".$this->_clsnumber."() { params" .$this->_clsnumber ." = 'page".$this->_clsnumber."=1'; updateTable".$this->_clsnumber."(); }\n";
	echo "</script>\n";
}
}

/*
// tombol filters 
$x->allowFilters(); 
// tombol reset grid control 
$x->showReset(); 

// headers text 
$x->setColumnHeader('FirstName', 'First Name'); 
$x->setColumnHeader('LastName', 'Last Name'); 

// Hide ID Column 
$x->hideColumn('Id'); 

// column type 
$x->setColumnType('FirstName', MultiGridcls::TYPE_HREF, 'http://google.com/search?q=%FirstName%'); // Google Me! 
$x->setColumnType('BirthDate', MultiGridcls::TYPE_DATE, 'M d, Y', true); // Change the date format 
$x->setColumnType('Gender', MultiGridcls::TYPE_ARRAY, array('m' => 'Male', 'f' => 'Female')); // Convert db values to something better 
$x->setColumnType('Done', MultiGridcls::TYPE_PERCENT, false, array('Back' => '#c3daf9', 'Fore' => 'black')); 
// Add custom control, order does matter 
$x->addCustomControl(MultiGridcls::CUSCTRL_TEXT, "alert('%FirstName%\'s been promoted!')", MultiGridcls::TYPE_ONCLICK, 'Promote Me'); 
// Add standard control 
$x->addStandardControl(MultiGridcls::STDCTRL_EDIT, "alert('Editing %LastName%, %FirstName% (ID: %_P%)')"); 
$x->addStandardControl(MultiGridcls::STDCTRL_DELETE, "alert('Deleting %_P%')"); 
// Add create control 
$x->showCreateButton("alert('Code for creating a new person')", MultiGridcls::TYPE_ONCLICK, 'Add New Person'); 

// kolom checkboxes 
$x->showCheckboxes(); 

// row numbers 
$x->showRowNumber(); 

// custom function 
function returnSomething($lastname) 
{ 
    return strrev($lastname); 
} 
$x->setColumnType('LastName', MultiGridcls::TYPE_FUNCTION, 'returnSomething', '%LastName%'); 
	
*/


?>