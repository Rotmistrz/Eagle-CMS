<?php

require '../../eagle-dependencies-dev.php';

use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase {
    private $testType = 100;
    private $firstItemHeader = "Lorem ipsum";
    private $secondItemContent = "Dolorsit amet";

    private $firstItem;
    private $secondItem;

    public function setUp() {
        $firstItemId = 100;
        $secondItemId = 101;

        $firstItemOrder = Orderable::ORDER_STEP;
        $secondItemOrder = 2 * Orderable::ORDER_STEP;

        $pdo = DataBase::getInstance();

        $query = "INSERT INTO " . ITEMS_TABLE . " (id, type, sort) VALUES (:id_00, :type, :sort_00), (:id_01, :type, :sort_01)";
        $inserting = $pdo->prepare($query);
        $inserting->bindValue(':id_00', $firstItemId, PDO::PARAM_INT);
        $inserting->bindValue(':type', $this->testType, PDO::PARAM_INT);
        $inserting->bindValue(':sort_00', $firstItemOrder, PDO::PARAM_INT);
        $inserting->bindValue(':id_01', $secondItemId, PDO::PARAM_INT);
        $inserting->bindValue(':sort_01', $secondItemOrder, PDO::PARAM_INT);

        if($inserting->execute() != 2) {
            throw new Exception("Error: preparing test was failed.");
        }

        $this->firstItem = new Item($firstItemId, $this->testType, $firstItemOrder);
        $this->secondItem = new Item($secondItemId, $this->testType, $secondItemOrder);
    }

    public function tearDown() {
        $query = "DELETE FROM " . ITEMS_TABLE . " WHERE type = :type";
        $pdo = DataBase::getInstance();

        $clearing = $pdo->prepare($query);
        $clearing->bindValue(':type', $this->testType, PDO::PARAM_INT);
        $clearing->execute();

        $pdo->query("ALTER TABLE " . ITEMS_TABLE . " AUTO_INCREMENT = 1");
    }

    public function testCreate() {
        $item = Item::create($this->testType);

        $pdo = DataBase::getInstance();

        $query = "SELECT id, type FROM " . ITEMS_TABLE . " ORDER BY id DESC LIMIT 1";
        $loading = $pdo->prepare($query);
        $loading->execute();

        $result = $loading->fetch();

        $this->assertEquals($result['id'], $item->getId());
    }

    public function testDelete() {
        $itemId = $this->firstItem->getId();

        $this->firstItem->delete();

        $pdo = DataBase::getInstance();

        $query = "SELECT id, type FROM " . ITEMS_TABLE . " WHERE id = :id";
        $loading = $pdo->prepare($query);
        $loading->bindValue(':id', $itemId, PDO::PARAM_INT);
        $loading->execute();

        $this->assertFalse($loading->fetch());
    }

    public function testSortIncrement() {
        $firstItem = Item::create($this->testType);
        $secondItem = Item::create($this->testType);

        $query = "SELECT id, type, sort FROM " . ITEMS_TABLE . " WHERE sort > :sort AND type = :type ORDER BY sort ASC";
        $pdo = DataBase::getInstance();

        $loading = $pdo->prepare($query);
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

        $pdo = DataBase::getInstance();

        $query = "SELECT * FROM " . ITEMS_TABLE . " WHERE id = :id";
        $loading = $pdo->prepare($query);
        $loading->bindValue(':id', $this->firstItem->getId(), PDO::PARAM_INT);
        $loading->execute();

        $result = $loading->fetch();

        $this->assertEquals($header, $result[LanguagableElement::getDatabaseFieldname(Item::HEADER_1, Language::PL)]);
        $this->assertEquals($content, $result[LanguagableElement::getDatabaseFieldname(Item::CONTENT_1, Language::EN)]);
    }

    public function testHide() {
        $this->firstItem->hide();

        $pdo = DataBase::getInstance();
        $query = "SELECT * FROM " . ITEMS_TABLE . " WHERE id = :id";

        $loading = $pdo->prepare($query);
        $loading->bindValue(':id', $this->firstItem->getId(), PDO::PARAM_INT);
        $loading->execute();

        $result = $loading->fetch();

        $this->assertFalse($this->firstItem->isVisible());
        $this->assertEquals($result['visible'], 0);
    }

    public function testShow() {
        $pdo = DataBase::getInstance();

        $query = "UPDATE " . ITEMS_TABLE . " set visible = :visible WHERE id = :id";
        $inserting = $pdo->prepare($query);
        $inserting->bindValue(':id', $this->firstItem->getId(), PDO::PARAM_INT);
        $inserting->bindValue(':visible', false, PDO::PARAM_INT);
        $inserting->execute();

        $checkingQuery = "SELECT * FROM " . ITEMS_TABLE . " WHERE id = :id";
        $loading = $pdo->prepare($checkingQuery);
        $loading->bindValue(':id', $this->firstItem->getId(), PDO::PARAM_INT);
        $loading->execute();

        $result = $loading->fetch();

        $this->assertEquals($result['visible'], 0);

        $this->firstItem->show();

        $finalCheckingQuery = "SELECT * FROM " . ITEMS_TABLE . " WHERE id = :id";
        $finalLoading = $pdo->prepare($finalCheckingQuery);
        $finalLoading->bindValue(':id', $this->firstItem->getId(), PDO::PARAM_INT);
        $finalLoading->execute();

        $finalResult = $finalLoading->fetch();

        $this->assertTrue($this->firstItem->isVisible());
        $this->assertEquals($finalResult['visible'], 1);
    }
}

?>