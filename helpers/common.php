<?php if (!defined('ROOT_PATH'))
    exit('No direct script access allowed');

/*
 * This function redirects the user to a page.
 */
function redirect($path)
{
    ob_start();

    if ($path == "/") {
        header("Location: " . URLROOT);
    } else {
        header("Location: " . URLROOT . "/" . $path);
    }
    exit();
}

/*
 * This function is used for dying and dumping.
 */
function dd($value, $flag = false)
{
    echo "<pre>";
    if ($flag)
        var_dump($value);
    else
        print_r($value);
    echo "</pre>";
}

/*
 * This function displays a session variable's value if it exists.
 */
function session($name)
{
    return $_SESSION[$name] ?? false;
}

/*
 * This function displays a session variable's value if it exists.
 */
function session_set($name, $value)
{
    return $_SESSION[$name] = $value;
}

/*
 * This function displays a session variable's value and unsets it if it exists.
 */
function session_once($name)
{
    if (isset($_SESSION[$name])) {
        $value = $_SESSION[$name];
        unset($_SESSION[$name]);
        return $value;
    }
    return "";
}

/*
 * This function sets or clears multiple session variables at once.
 * Pass an associative array where keys are session variable names.
 * - To set a variable, provide the value
 * - To unset a variable, pass null as the value
 */
function session_set_multiple($data)
{
    if (!is_array($data)) {
        return false;
    }

    foreach ($data as $key => $value) {
        if ($value === null) {
            unset($_SESSION[$key]);
        } else {
            $_SESSION[$key] = $value;
        }
    }

    return true;
}



/*
- Trim whitespace
- Remove HTML & PHP tags to prevent XSS attacks
- Escape special characters for safe database storage
- Ensure an associative array is returned
*/

function sanitize($data)
{
    if (is_array($data)) {
        // Recursively sanitize arrays
        return array_map('sanitize', $data);
    } elseif (is_numeric($data)) {
        // Preserve numeric values
        return $data;
    } elseif (!empty($data)) {
        // Trim, remove tags, and escape special characters
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    } else {
        return $data;
    }
}
/*
 * This function generates a URL-friendly slug for organizations.
 */
function slug($text)
{
    $text = strtolower($text);
    return trim(preg_replace('/[^a-z0-9]+/', '-', $text), '-') . '-' . base_convert((int) (microtime(true) * 1000), 10, 36); //current time in milliseconds converted to short alphanumeric strings
}
