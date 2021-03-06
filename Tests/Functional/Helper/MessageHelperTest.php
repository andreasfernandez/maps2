<?php

/*
 * This file is part of the package jweiland/maps2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Maps2\Tests\Functional\Helper;

use JWeiland\Maps2\Helper\MessageHelper;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;

/**
 * Test MessageHelper
 */
class MessageHelperTest extends FunctionalTestCase
{
    /**
     * @var MessageHelper
     */
    protected $subject;

    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/maps2'
    ];

    protected function setUp()
    {
        parent::setUp();
        parent::setUpBackendUserFromFixture(1);

        $this->subject = new MessageHelper(
            new FlashMessageService()
        );
    }

    protected function tearDown()
    {
        unset(
            $this->subject,
            $this->flashMessageServiceProphecy
        );
        parent::tearDown();
    }

    /**
     * @test
     */
    public function addFlashMessageWithMessageCallsEnqueue()
    {
        $expectedFlashMessage = new FlashMessage(
            'Hello',
            '',
            FlashMessage::OK,
            true
        );

        $this->subject->addFlashMessage('Hello');

        self::assertEquals(
            [$expectedFlashMessage],
            $this->subject->getAllFlashMessages()
        );
    }

    /**
     * @test
     */
    public function addFlashMessageWithMessageAndSubjectCallsEnqueue()
    {
        $expectedFlashMessage = new FlashMessage(
            'Hello',
            'Subject',
            FlashMessage::OK,
            true
        );

        $this->subject->addFlashMessage('Hello', 'Subject');

        self::assertEquals(
            [$expectedFlashMessage],
            $this->subject->getAllFlashMessages()
        );
    }

    /**
     * @test
     */
    public function addFlashMessageWithAllArgumentsCallsEnqueue()
    {
        $expectedFlashMessage = new FlashMessage(
            'Hello',
            'Subject',
            FlashMessage::ERROR,
            true
        );

        $this->subject->addFlashMessage('Hello', 'Subject', FlashMessage::ERROR);

        self::assertEquals(
            [$expectedFlashMessage],
            $this->subject->getAllFlashMessages()
        );
    }

    /**
     * @test
     */
    public function getAllFlashMessagesReturnsAllFlashMessages()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 0);
        $this->subject->addFlashMessage('all', 'all', 1);
        $this->subject->addFlashMessage('together', 'together', 2);

        // Test two times, to be save that messages were NOT flushed
        self::assertCount(
            3,
            $this->subject->getAllFlashMessages(false)
        );
        self::assertCount(
            3,
            $this->subject->getAllFlashMessages(false)
        );
    }

    /**
     * @test
     */
    public function getAllFlashMessagesReturnsAllFlashMessagesAndFlush()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 0);
        $this->subject->addFlashMessage('all', 'all', 1);
        $this->subject->addFlashMessage('together', 'together', 2);

        // Test two times, to be save that messages were flushed
        self::assertCount(
            3,
            $this->subject->getAllFlashMessages(true)
        );
        self::assertCount(
            0,
            $this->subject->getAllFlashMessages(true)
        );
    }

    /**
     * @test
     */
    public function hasMessagesChecksQueueIfThereAreAnyMessages()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 0);
        $this->subject->addFlashMessage('all', 'all', 1);
        $this->subject->addFlashMessage('together', 'together', 2);

        self::assertTrue(
            $this->subject->hasMessages()
        );
    }

    /**
     * @test
     */
    public function getFlashMessagesBySeverityAndFlushReturnsFlashMessages()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 1);
        $this->subject->addFlashMessage('all', 'all', 1);
        $this->subject->addFlashMessage('together', 'together', 2);

        // Test two times, to be save that messages were flushed
        self::assertCount(
            1,
            $this->subject->getFlashMessagesBySeverityAndFlush(FlashMessage::ERROR)
        );
        self::assertCount(
            0,
            $this->subject->getFlashMessagesBySeverityAndFlush(FlashMessage::ERROR)
        );

        // Test two times, to be save that messages were flushed
        self::assertCount(
            2,
            $this->subject->getFlashMessagesBySeverityAndFlush(FlashMessage::WARNING)
        );
        self::assertCount(
            0,
            $this->subject->getFlashMessagesBySeverityAndFlush(FlashMessage::WARNING)
        );
    }

    /**
     * @test
     */
    public function hasErrorMessagesReturnsTrue()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 1);
        $this->subject->addFlashMessage('all', 'all', 2);
        $this->subject->addFlashMessage('together', 'together', 2);

        self::assertTrue(
            $this->subject->hasErrorMessages()
        );
    }

    /**
     * @test
     */
    public function hasErrorMessagesReturnsFalse()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 1);
        $this->subject->addFlashMessage('all', 'all', 0);
        $this->subject->addFlashMessage('together', 'together', 0);

        self::assertFalse(
            $this->subject->hasErrorMessages()
        );
    }

    /**
     * @test
     */
    public function getErrorMessagesReturnsErrorMessages()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 1);
        $this->subject->addFlashMessage('all', 'all', 2);
        $this->subject->addFlashMessage('together', 'together', 2);

        self::assertCount(
            2,
            $this->subject->getErrorMessages()
        );
    }

    /**
     * @test
     */
    public function hasWarningMessagesReturnsTrue()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 0);
        $this->subject->addFlashMessage('all', 'all', 1);
        $this->subject->addFlashMessage('together', 'together', 1);

        self::assertTrue(
            $this->subject->hasWarningMessages()
        );
    }

    /**
     * @test
     */
    public function hasWarningMessagesReturnsFalse()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 0);
        $this->subject->addFlashMessage('all', 'all', 2);
        $this->subject->addFlashMessage('together', 'together', 2);

        self::assertFalse(
            $this->subject->hasWarningMessages()
        );
    }

    /**
     * @test
     */
    public function getWarningMessagesReturnsErrorMessages()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 0);
        $this->subject->addFlashMessage('all', 'all', 1);
        $this->subject->addFlashMessage('together', 'together', 1);

        self::assertCount(
            2,
            $this->subject->getWarningMessages()
        );
    }

    /**
     * @test
     */
    public function hasOkMessagesReturnsTrue()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 1);
        $this->subject->addFlashMessage('all', 'all', 0);
        $this->subject->addFlashMessage('together', 'together', 0);

        self::assertTrue(
            $this->subject->hasOkMessages()
        );
    }

    /**
     * @test
     */
    public function hasOkMessagesReturnsFalse()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 2);
        $this->subject->addFlashMessage('all', 'all', 1);
        $this->subject->addFlashMessage('together', 'together', 1);

        self::assertFalse(
            $this->subject->hasOkMessages()
        );
    }

    /**
     * @test
     */
    public function getOkMessagesReturnsErrorMessages()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 1);
        $this->subject->addFlashMessage('all', 'all', 0);
        $this->subject->addFlashMessage('together', 'together', 0);

        self::assertCount(
            2,
            $this->subject->getOkMessages()
        );
    }

    /**
     * @test
     */
    public function hasInfoMessagesReturnsTrue()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 1);
        $this->subject->addFlashMessage('all', 'all', -1);
        $this->subject->addFlashMessage('together', 'together', -1);

        self::assertTrue(
            $this->subject->hasInfoMessages()
        );
    }

    /**
     * @test
     */
    public function hasInfoMessagesReturnsFalse()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 2);
        $this->subject->addFlashMessage('all', 'all', 1);
        $this->subject->addFlashMessage('together', 'together', 1);

        self::assertFalse(
            $this->subject->hasInfoMessages()
        );
    }

    /**
     * @test
     */
    public function getInfoMessagesReturnsErrorMessages()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 1);
        $this->subject->addFlashMessage('all', 'all', -1);
        $this->subject->addFlashMessage('together', 'together', -1);

        self::assertCount(
            2,
            $this->subject->getInfoMessages()
        );
    }

    /**
     * @test
     */
    public function hasNoticeMessagesReturnsTrue()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 1);
        $this->subject->addFlashMessage('all', 'all', -2);
        $this->subject->addFlashMessage('together', 'together', -2);

        self::assertTrue(
            $this->subject->hasNoticeMessages()
        );
    }

    /**
     * @test
     */
    public function hasNoticeMessagesReturnsFalse()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 2);
        $this->subject->addFlashMessage('all', 'all', 1);
        $this->subject->addFlashMessage('together', 'together', 1);

        self::assertFalse(
            $this->subject->hasNoticeMessages()
        );
    }

    /**
     * @test
     */
    public function getNoticeMessagesReturnsErrorMessages()
    {
        $this->subject->addFlashMessage('Hello', 'Hello', 1);
        $this->subject->addFlashMessage('all', 'all', -2);
        $this->subject->addFlashMessage('together', 'together', -2);

        self::assertCount(
            2,
            $this->subject->getNoticeMessages()
        );
    }
}
