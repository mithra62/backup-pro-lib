<?php
use aik099\PHPUnit\BrowserTestCase;

class GeneralTest extends BrowserTestCase
{

    public static $browsers = array(
        array(
            'driver' => 'selenium2',
            'host' => 'localhost',
            'port' => 4444,
            'browserName' => 'firefox',
            'baseUrl' => 'http://eric.ee2.clean.mithra62.com',
        ),
    );

    public function testUsingSession()
    {
        // This is Mink's Session.
        $session = $this->getSession();

        // Go to a page.
        $session->visit('http://eric.ee2.clean.mithra62.com/admin.php?/cp/login&return=');

        // Validate text presence on a page.
        $this->assertTrue($session->getPage()->hasContent('Username'));
    }

    public function testUsingBrowser()
    {
        // Prints the name of used browser.
        echo sprintf(
            "I'm executed using '%s' browser",
            $this->getBrowser()->getBrowserName()
        );
    }

}