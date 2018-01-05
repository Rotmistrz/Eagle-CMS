<?php

require '../../eagle-dependencies-dev.php';

use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase {
    private $testType = 100;

    private $firstItemOrder = Orderable::ORDER_STEP;
    private $secondItemOrder = 2 * Orderable::ORDER_STEP;
    private $firstItemHeader = "Lorem ipsum";
    private $firstItemContent = "Dolor sit amet";
    private $secondItemHeader = "Dolor sit amet";
    private $secondItemContent = "Lorem ipsum";


    private $firstItem;
    private $secondItem;

    private $pdo;

    public function __construct() {
        parent::__construct();

        $this->pdo = DataBase::getInstance();
    }

    public function setUp() {
        $firstItemId = 100;
        $secondItemId = 101;

        $query = "INSERT INTO " . ITEMS_TABLE . " (id, type, " . LanguagableElement::getDatabaseFieldname(Item::HEADER_1, Language::PL) . ", " . LanguagableElement::getDatabaseFieldname(Item::CONTENT_1, Language::EN) . ", sort) VALUES (:id_00, :type, :header_00, :content_00, :sort_00), (:id_01, :type, :header_01, :content_01, :sort_01)";

        $inserting = $this->pdo->prepare($query);
        $inserting->bindValue(':type', $this->testType, PDO::PARAM_INT);

        $inserting->bindValue(':id_00', $firstItemId, PDO::PARAM_INT);
        $inserting->bindValue(':header_00', $this->firstItemHeader, PDO::PARAM_STR);
        $inserting->bindValue(':content_00', $this->firstItemContent, PDO::PARAM_STR);
        $inserting->bindValue(':sort_00', $this->firstItemOrder, PDO::PARAM_INT);

        $inserting->bindValue(':id_01', $secondItemId, PDO::PARAM_INT);
        $inserting->bindValue(':header_01', $this->secondItemHeader, PDO::PARAM_STR);
        $inserting->bindValue(':content_01', $this->secondItemContent, PDO::PARAM_STR);
        $inserting->bindValue(':sort_01', $this->secondItemOrder, PDO::PARAM_INT);

        if($inserting->execute() != 2) {
            throw new Exception("Error: preparing test was failed.");
        }

        $this->firstItem = new Item($firstItemId, $this->testType, $this->firstItemOrder);
        $this->firstItem->setContent(Language::PL, Item::HEADER_1, $this->firstItemHeader);
        $this->firstItem->setContent(Language::EN, Item::CONTENT_1, $this->firstItemContent);

        $this->secondItem = new Item($secondItemId, $this->testType, $this->secondItemOrder);
        $this->secondItem->setContent(Language::PL, Item::HEADER_1, $this->secondItemHeader);
        $this->secondItem->setContent(Language::EN, Item::CONTENT_1, $this->secondItemContent);
    }

    public function tearDown() {
        $query = "DELETE FROM " . ITEMS_TABLE . " WHERE type = :type";

        $clearing = $this->pdo->prepare($query);
        $clearing->bindValue(':type', $this->testType, PDO::PARAM_INT);
        $clearing->execute();

        $this->pdo->query("ALTER TABLE " . ITEMS_TABLE . " AUTO_INCREMENT = 1");
    }

    public function testCreate() {
        $item = Item::create($this->testType);

        $query = "SELECT id, type FROM " . ITEMS_TABLE . " ORDER BY id DESC LIMIT 1";
        $loading = $this->pdo->prepare($query);
        $loading->execute();

        $result = $loading->fetch();

        $this->assertEquals($result['id'], $item->getId());
    }

    public function testDelete() {
        $itemId = $this->firstItem->getId();

        $this->firstItem->delete();

        $query = "SELECT id, type FROM " . ITEMS_TABLE . " WHERE id = :id";
        $loading = $this->pdo->prepare($query);
        $loading->bindValue(':id', $itemId, PDO::PARAM_INT);
        $loading->execute();

        $this->assertFalse($loading->fetch());
    }

    public function testSortIncrement() {
        $firstItem = Item::create($this->testType);
        $secondItem = Item::create($this->testType);

        $query = "SELECT id, type, sort FROM " . ITEMS_TABLE . " WHERE sort > :sort AND type = :type ORDER BY sort ASC";

        $loading = $this->pdo->prepare($query);
        $loading->bindValue(':sort', $firstItem->getOrder(), PDO::PARAM_INT);
        $loading->bindValue(':type', $this->testType, PDO::PARAM_INT);
        $loading->execute();

        $result = $loading->fetch();

        $this->assertEquals($result['sort'], $firstItem->getOrder() + Orderable::ORDER_STEP);
        $this->assertEquals($result['id'], $secondItem->getId());
    }

    public function testEarlierOne() {
        $previousId = $this->firstItem->getId();

        $anotherItem = $this->secondItem->getEarlierOne();

        $this->assertEquals($anotherItem->getId(), $previousId);
        $this->assertEquals($anotherItem->getOrder(), $this->secondItem->getOrder() - Orderable::ORDER_STEP);
        $this->assertEquals($anotherItem, $this->firstItem);
    }

    public function testLaterOne() {
        $nextId = $this->secondItem->getId();

        $anotherItem = $this->firstItem->getLaterOne();

        $this->assertEquals($anotherItem->getId(), $nextId);
        $this->assertEquals($anotherItem->getOrder(), $this->firstItem->getOrder() + Orderable::ORDER_STEP);
    }

    public function testSave() {
        $header = "Lorem ipsum";
        $content = "Dolor sit amet";

        $this->firstItem->setContent(Language::PL, Item::HEADER_1, $header);
        $this->firstItem->setContent(Language::EN, Item::CONTENT_1, $content);

        if(!$this->firstItem->save()) {
            $this->fail("Failure while saving.");
        }

        $query = "SELECT * FROM " . ITEMS_TABLE . " WHERE id = :id";
        $loading = $this->pdo->prepare($query);
        $loading->bindValue(':id', $this->firstItem->getId(), PDO::PARAM_INT);
        $loading->execute();

        $result = $loading->fetch();

        $this->assertEquals($header, $result[LanguagableElement::getDatabaseFieldname(Item::HEADER_1, Language::PL)]);
        $this->assertEquals($content, $result[LanguagableElement::getDatabaseFieldname(Item::CONTENT_1, Language::EN)]);
    }

    public function testHide() {
        $this->firstItem->hide();

        $query = "SELECT * FROM " . ITEMS_TABLE . " WHERE id = :id";

        $loading = $this->pdo->prepare($query);
        $loading->bindValue(':id', $this->firstItem->getId(), PDO::PARAM_INT);
        $loading->execute();

        $result = $loading->fetch();

        $this->assertFalse($this->firstItem->isVisible());
        $this->assertEquals($result['visible'], 0);
    }

    public function testShow() {
        $query = "UPDATE " . ITEMS_TABLE . " set visible = :visible WHERE id = :id";
        $inserting = $this->pdo->prepare($query);
        $inserting->bindValue(':id', $this->firstItem->getId(), PDO::PARAM_INT);
        $inserting->bindValue(':visible', false, PDO::PARAM_INT);
        $inserting->execute();

        $checkingQuery = "SELECT * FROM " . ITEMS_TABLE . " WHERE id = :id";
        $loading = $this->pdo->prepare($checkingQuery);
        $loading->bindValue(':id', $this->firstItem->getId(), PDO::PARAM_INT);
        $loading->execute();

        $result = $loading->fetch();

        $this->assertEquals($result['visible'], 0);

        $this->firstItem->show();

        $finalCheckingQuery = "SELECT * FROM " . ITEMS_TABLE . " WHERE id = :id";
        $finalLoading = $this->pdo->prepare($finalCheckingQuery);
        $finalLoading->bindValue(':id', $this->firstItem->getId(), PDO::PARAM_INT);
        $finalLoading->execute();

        $finalResult = $finalLoading->fetch();

        $this->assertTrue($this->firstItem->isVisible());
        $this->assertEquals($finalResult['visible'], 1);
    }

    public function testLoad() {
        $item = Item::load($this->firstItem->getId());

        $this->assertEquals($item, $this->firstItem);
        $this->assertEquals($this->firstItemOrder, $item->getOrder());
        $this->assertEquals($this->firstItemHeader, $item->getContent(Language::PL, Item::HEADER_1));
        $this->assertEquals($this->firstItemContent, $item->getContent(Language::EN, Item::CONTENT_1));
        $this->assertTrue($item->isVisible());
    }

    public function testGetFollowingOrder() {
        $followingOrder = Item::getFollowingOrder($this->testType);

        $this->assertEquals($followingOrder, $this->secondItemOrder + Orderable::ORDER_STEP);
    }

    public function testGetContentsByLanguage() {
        $polishContents = $this->firstItem->getContentsByLanguage(Language::PL);
        $englishContents = $this->firstItem->getContentsByLanguage(Language::EN);

        $this->assertTrue(array_key_exists(Item::HEADER_1, $polishContents));
        $this->assertEquals($this->firstItemHeader, $polishContents[Item::HEADER_1]);

        $this->assertTrue(array_key_exists(Item::CONTENT_1, $englishContents));
        $this->assertEquals($this->firstItemContent, $englishContents[Item::CONTENT_1]);
    }
}

?>