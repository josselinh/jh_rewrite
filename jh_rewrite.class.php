<?php
/**
 * JhRewrite
 * @name JhRewrite
 * @version 1.1
 * @date 2012-06-15
 */
class JhRewrite
{
    protected $mode;
    protected $routes;
    protected $request;

    /**
     * Default construct.
     * You can set $mode.
     * @param $mode string ("query" or "path")
     */
    public function __construct($mode = 'path')
    {
        $this->empty = false;
        $this->setMode($mode);
        $this->getRequest();

    }

    /**
     * setMode.
     * Set the request mode.
     * @param $mode string ("query" or "path")
     */
    protected function setMode($mode = 'path')
    {
        if (!in_array($mode, array('query', 'path'))) {
            throw new Exception("Mode is &lt;query&gt; or &lt;path&gt;");
            $this->mode = 'query';
        }

        $this->mode = $mode;
    }

    /**
     * getRequest.
     * Get request automatically by $mode.
     */
    public function getRequest()
    {
        if ($this->mode == 'query') {
            $this->setRequest($_SERVER['QUERY_STRING']);
        }

        if ($this->mode == 'path') {
            $this->setRequest(substr($_SERVER['PATH_INFO'], 1));
        }
    }

    /**
     * setRequest.
     * Set the user request to be processed.
     * @param $request (string) it is the url rewritten.
     */
    public function setRequest($request = null)
    {
        $this->request = $request;
    }

    /**
     * addRoute.
     * Add a route
     * @param $name string name of the route.
     * @param $rewrite string url rewritten
     * @param $href mixed string or array
     * @return boolean
     */
    public function addRoute($name = null, $rewrite = null, $href = array())
    {
        if (isset($this->routes[$name])) {
            throw new Exception("Route &lt;" . $name . "&gt; already exists !");
            return false;
        }

        $this->routes[$name] = array('rewrite' => $rewrite, 'href' => $href);
        return true;
    }

    /**
     * url.
     * Get the url rewritten by its name.
     * @param $url_name string name of the url rewritten.
     * @param $args array arguments
     * @return string url rewritten
     */
    public function url($url_name = null, $args = array())
    {
        $rewrite = $this->prepareUrl($url_name, $args);

        if ($rewrite) {
            if ($this->mode == 'query') {
                $rewrite = '?' . $rewrite;
            }

            if ($this->mode == 'path') {
                $rewrite = $_SERVER['SCRIPT_NAME'] . '/' . $rewrite;
            }
        } else {
            $rewrite = '#';
        }

        return $rewrite;
    }

    /**
     * prepareUrl.
     * Prepare the content of url rewritten
     * @param $url_name string name of the url rewritten.
     * @param $args array arguments
     * @return string|boolean
     */
    public function prepareUrl($url_name = null, $args = array())
    {
        foreach ($this->routes as $name => $params) {
            if ($url_name == $name) {
                $rewrite = $params['rewrite'];

                if (preg_match_all('#<:(.*)>#Ui', $params['rewrite'], $matches_rewrite)) {
                    if (count($matches_rewrite[0]) > 0) {
                        foreach ($matches_rewrite[0] as $key => $arg) {
                            $rewrite = str_replace($arg, $args[$matches_rewrite[1][$key]], $rewrite);
                        }
                    }
                }

                return $rewrite;
            }
        }

        return false;
    }

    /**
     * dispatch.
     * Process all routes.
     * @param $default_url_name string name of the url rewritten.
     * @param $default_args array arguments
     * @return array array('href', 'args')
     */
    public function dispatch($default_url_name = null, $default_args = array())
    {
        if ($this->request == null) {
            $this->request = $this->prepareUrl($default_url_name, $default_args);
        }

        foreach ($this->routes as $name => $params) {
            $params['rewrite'] = str_replace(array('.'), array('\.'), $params['rewrite']);
            $rewrite_expression = $params['rewrite'];
            $matches_rewrite = array();
            $matches_request = array();

            if (preg_match_all('#<:(.*)>#Ui', $params['rewrite'], $matches_rewrite)) {
                $rewrite_expression = preg_replace('#<:(.*)>#Ui', '(.*)', $params['rewrite']);
            }

            if (preg_match('#^' . $rewrite_expression . '$#', $this->request, $matches_request)) {
                $gets = array();

                if (count($matches_rewrite[0]) > 0) {
                    foreach ($matches_rewrite[1] as $key => $arg) {
                        $gets[$arg] = $matches_request[$key + 1];
                    }
                }

                if ($this->mode == 'path') {
                    $gets_parse_str = array();
                    parse_str($_SERVER['QUERY_STRING'], $gets_parse_str);

                    if (count($gets_parse_str) > 0) {
                        $gets += $gets_parse_str;
                    }
                }

                return array('href' => $params['href'], 'args' => $gets);
            }
        }
    }
}