<!DOCTYPE html>
<html>
<head>
	<title>Elastic Search Here - Tauheed Maniar</title>
</head>
<body>
	<center>
		<h2>Algolia</h2>
		<form action="unification.php" method="POST" style="padding-top: 75px">
			<center>
			<label>
				Term 1: 
			</label>
			<input type="text" name="level1" required>
			</center>
			<br>
			<center>
			<label>
				Term 2: 
			</label>
			<input type="text" name="level2" required>
			</center>
			<br>
			<button type="submit">Elastic</button>
		</form>
	</center>

	<?php
	    $level1 = isset($_POST['level1']) ? $_POST['level1'] : null;
	    $level2 = isset($_POST['level2']) ? $_POST['level2'] : null;
	    $errorMsg = 0;

	    /*
	    *	To check whether the given value is Function or Variable/Constant
	    **/
	    function isFunc($value) {
	    	if(strpos($value, '(') !== false) {
	    		return 1;
	    	}
	    	return 0;
	    }

	    /*
	    *	Check if no null value is passed then count the parameters passed are same or not
        *   It means that to check if the input is passed blank! If it is blank then the else condition will
        *   get executed.
	    **/
	    if((!is_null($level1)) || (!is_null($level2))) {
            if((strpos($level1, '(') !== false) && (strpos($level2, '(') !== false)) {
    	        if(count(explode(',', $level1)) != count(explode(',', $level2))) {
    	        	$errorMsg = 1;
    				if($errorMsg) {
    			?>
    					<center><h2 style="color: orange">Function Length is different</h2></center>

    			<?php
    					return FALSE;
    				}
    	        }
            }
	    } else {
			$errorMsg = 1;
			if($errorMsg) {
		?>
				<center><h2 style="color: orange">Text Empty</h2></center>
		<?php
				return FALSE;
			}
    	}

    	/*
		*	Assigning value based on the input with parameters or without any paranthesis
        *   It means if term-1 is only 'X', then if condition will get executed and if the term-1 input field contains any this '(' type of parameter then it will execute the else condition code.
    	**/
    	if((strpos($level1, '(') === false)) {
    		$term1 = explode(',', $level1);
    	} else {
    		$term1 = explode(',', substr($level1, strpos($level1, '(')+1, strrpos($level1, ')')-2));
    		
            /*if(substr($level1, 0, 1) != substr($level2, 0, 1)) {
                $errorMsg = 1;
                if($errorMsg) {
            ?>
                    <center><h2 style="color: orange">Function starting name character does not match!</h2></center>
            <?php
                return FALSE;
                }
            }*/
    	}

        /*
        *   Assigning value based on the input with parameters or without any paranthesis
        *   It means if term-2 is only 'X', then if condition will get executed and if the term-2 input field contains any this '(' type of parameter then it will execute the else condition code.
        **/
        if(strpos($level2, '(') === false) {
            $term2 = explode(',', $level2);
        } else {
            $term2 = explode(',', substr($level2, strpos($level2, '(')+1, strrpos($level2, ')')-2));
            
            /*if(substr($level1, 0, 1) != substr($level2, 0, 1)) {
                $errorMsg = 1;
                if($errorMsg) {
            ?>
                    <center><h2 style="color: orange">Function starting name character does not match!</h2></center>
            <?php
                return FALSE;
                }
            }*/
        }
        

    /*function getCount($value) {
    	$arr = explode(',', $value);
    	return count($arr);
    }*/

    /*
     *	To check if input start with same character, for example "f() & f()"
    **/
    function checkFuncStartingChar($value1, $value2, $k) {
    	if(substr($value1[$k], 0, 1) != substr($value2[$k], 0, 1)) {
		?>
			<script type="text/javascript">
				alert('Failed');
			</script>		
		<?php
		}
    }
   
    $arr1 = $arr2 = [];
    $i = $same = 0;

    /*
	*	Substitution begins here
    **/
    while($i < count($term1)) {

    	$same = 0;

        /*
         *  if the term-1 value comes like 'X' and term-2 value comes like 'h(a,Y)' then the if condition code will get executed,
         *  And Vice Versa for elseif condition to execute
        **/
        if((count($term1) < count($term2) && ctype_upper($term1[$i])) || (!(isFunc($level1)) && (isFunc($level2)))) {
        ?>
            <center><h2 style="color: green">YES </h2></center>
            <center><h2 style="color: green"><?php echo $term1[$i].' = ' .$level2; ?></h2></center>
            <center><h2 style="color: green"><?php echo $_POST['level1'] ." <=> ". $_POST['level2']; ?> </h2></center>
        <?php
            return FALSE;
        } elseif(count($term1) > count($term2) && ctype_upper($term2[$i]) || (!(isFunc($level2)) && (isFunc($level1)))) {
        ?>
            <center><h2 style="color: green">YES </h2></center>
            <center><h2 style="color: green"><?php echo $term2[$i].' = ' .$level1; ?></h2></center>
            <center><h2 style="color: green"><?php echo $_POST['level1'] ." <=> ". $_POST['level2']; ?> </h2></center>
        <?php
            return FALSE;
        }

    	/*
         *  This condition substitute the variable and function as per the unification algorithm
        **/
        if(isFunc($term1[$i]) && isFunc($term2[$i])) {
    		checkFuncStartingChar($term1, $term2, $i);

    		if(count(explode(',', $term1[$i])) !== count(explode(',', $term2[$i]))) {
    		?>
    			<script type="text/javascript">
					alert('Failed');
				</script>		
			<?php
    		}

    		if($term1[$i] === $term2[$i]) {
    		
    			$i++;
                continue;

    		}
    		
    	} elseif(isFunc($term1[$i]) && ctype_upper($term2[$i])) {
    		if(in_array($term2[$i], $arr1)) {
                   
                $key = (array_search($term2[$i], $arr1));
                $arr1[$i] = $arr2[$key];
                $arr2[$i] = $term1[$i];
            /*} elseif(in_array($term1[$i], $arr2)) {
                echo $i.'COOL';
                $key = (array_search($term1[$i], $arr2));
                $arr1[$i] = $arr2[$key];
                $arr2[$i] = $term2[$i];*/
            } else {
                $arr1[$i] = $term2[$i];
                $arr2[$i] = $term1[$i];
            }
    	} elseif(ctype_upper($term1[$i]) && isFunc($term2[$i])) {
    		if(in_array($term1[$i], $arr1)) {
                $key = (array_search($term1[$i], $arr1));
                if(ctype_upper($arr2[$key])) {
                    $arr1[$i] = $arr2[$key];
                    $arr2[$key] = $term2[$i];
                    $arr2[$i] = $term2[$i];
                }
            } else {
                $arr1[$i] = $term1[$i];
                $arr2[$i] = $term2[$i];
            }
    	}

        if(ctype_lower($term1[$i]) && ctype_lower($term2[$i])) {
            if($term1[$i] === $term2[$i]) {
                $i++;
                $same = 1;
                continue;
            }
        }

        if(ctype_upper($term1[$i]) && ctype_upper($term2[$i])) {
            if($term1[$i] === $term2[$i]) {
                $i++;
                $same = 1;
                continue;
            }
        }

        if($term1[$i] != $term2[$i]) {
            if((ctype_upper($term1[$i]) && ctype_upper($term2[$i])) || (ctype_upper($term1[$i]) && ctype_lower($term2[$i]))) {

                if(in_array($term1[$i], $arr1)) {
                   
                    $key = (array_search($term1[$i], $arr1));
                    $arr1[$i] = $arr2[$key];
                    $arr2[$i] = $term2[$i];
                /*} elseif(in_array($term1[$i], $arr2)) {
                    echo $i.'COOL';
                    $key = (array_search($term1[$i], $arr2));
                    $arr1[$i] = $arr2[$key];
                    $arr2[$i] = $term2[$i];*/
                } else {
                    $arr1[$i] = $term1[$i];
                    $arr2[$i] = $term2[$i];
                }
            } elseif ((ctype_upper($term2[$i]) && ctype_lower($term1[$i]))) {
               
                if(in_array($term2[$i], $arr1)) {
                    $key = (array_search($term2[$i], $arr1));
                    if(ctype_upper($arr2[$key])) {
                        $arr1[$i] = $arr2[$key];
                        $arr2[$key] = $term1[$i];
                        $arr2[$i] = $term1[$i];
                    }
                    // $arr1[$i] = $arr2[$key];
                    // $arr2[$i] = $term2[$i];
                } else {
                    $arr1[$i] = $term2[$i];
                    $arr2[$i] = $term1[$i];
                }
            }
        }

        $i++;
    }
    $result = array_combine($arr1, $arr2);

    foreach($result as $k => $val) {
       
        if(ctype_lower($k)) {
            $result[$k] = $val;
            $result[$val] = $k;
            unset($result[$k]);
        }
        if(array_key_exists($val, $result)) {
            if(!ctype_lower($val)) {
                $result[$k] = $result[$val];
            }
        }
    }
    foreach ($result as $k => $val) {
        if(ctype_lower($k) && ctype_lower($val)) {
            unset($result[$k]);
        }
    }

    /*
    *	Final output being processed to display output
    **/
    if(!empty($result)) {
    ?>
    	<center><h2 style="color: green">YES </h2></center>
    <?php
		foreach ($result as $key => $value) {
	?>
			<center><h2 style="color: green"><?php echo $key ." = ". $value; ?> </h2></center>
	<?php
		}
	?>
		<center><h2 style="color: green"><?php echo $_POST['level1'] ." <=> ". $_POST['level2']; ?> </h2></center>
	<?php
	} elseif($same) {
	?>
		<center><h2 style="color: green">YES </h2></center>
		<center><h2 style="color: green"><?php echo $_POST['level1'] ." <=> ". $_POST['level2']; ?> </h2></center>
	<?php
	}
?>

</body>
</html>