<?php

require '../../eagle-dependencies-dev.php';

use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase {
    private $testType = 100;

    private $firstItemData;
    private $secondItemData;

    private $firstItem;
    private $secondItem;

    private $itemsData;
    private $items;

    private $itemsCollection;

    private $pdo;

    public function __construct() {
        parent::__construct();

        $this->pdo = DataBase::getInstance();
    }

    public function setUp() {
        $this->itemsData = [];
        $this->itemsData[100] = ['id' => 100, 'type' => $this->testType, 'order' => 0 * Orderable::ORDER_STEP, 'header' => "Lorem ipsum", 'content' => "Dolor sit amet"];
        $this->itemsData[101] = ['id' => 101, 'type' => $this->testType, 'order' => 1 * Orderable::ORDER_STEP, 'header' => "Dolor sit amet", 'content' => "Lorem ipsum"];
        $this->itemsData[102] = ['id' => 102, 'type' => $this->testType, 'order' => 2 * Orderable::ORDER_STEP, 'header' => "Aliquam et malesuada", 'content' => "Nulla consectetuer"];
        $this->itemsData[103] = ['id' => 103, 'type' => $this->testType, 'order' => 3 * Orderable::ORDER_STEP, 'header' => "Vestibulum ante", 'content' => "Aliquam erat gravida"];
        $this->itemsData[104] = ['id' => 104, 'type' => $this->testType, 'order' => 4 * Orderable::ORDER_STEP, 'header' => "Donec erat", 'content' => "Praesent est iaculis"];

        $this->firstItemData = $this->itemsData[100];
        $this->secondItemData = $this->itemsData[101];

        $this->items = [];
        $this->itemsCollection = new ItemsCollection();

        $query = "INSERT INTO " . ITEMS_TABLE . " (id, type, " . LanguagableElement::getDatabaseFieldname(Item::HEADER_1, Language::PL) . ", " . LanguagableElement::getDatabaseFieldname(Item::CONTENT_1, Language::EN) . ", sort) VALUES";

        $i = 0;

        for($i = 0; $i < count($this->itemsData); $i++) {
            $query .= " (:id_" . $i . ", :type_" . $i . ", :header_" . $i . ", :content_" . $i . ", :sort_" . $i . ")";

            if($i < count($this->itemsData) - 1) {
                $query .= ",";
            }
        }

        $inserting = $this->pdo->prepare($query);

        $i = 0;

        foreach($this->itemsData as $itemData) {
            $inserting->bindValue(':id_' . $i, $itemData['id'], PDO::PARAM_INT);
            $inserting->bindValue(':type_' . $i, $itemData['type'], PDO::PARAM_INT);
            $inserting->bindValue(':header_' . $i, $itemData['header'], PDO::PARAM_STR);
            $inserting->bindValue(':content_' . $i, $itemData['content'], PDO::PARAM_STR);
            $inserting->bindValue(':sort_' . $i, $itemData['order'], PDO::PARAM_INT);
            
            $item = new Item($itemData['id'], $itemData['type'], $itemData['order']);
            $item->setContent(Language::PL, Item::HEADER_1, $itemData['header']);
            $item->setContent(Language::EN, Item::CONTENT_1, $itemData['content']);
            
            $this->items[] = $item;
            $this->itemsCollection->add($item);

            $i++;
        }

        if($inserting->execute() != count($this->itemsData)) {
            throw new Exception("Error: preparing test was failed.");
        }

        $this->firstItem = $this->items[0];
        $this->secondItem = $this->items[1];
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

        $this->assertEquals($this->firstItem->getId(), $result['id']);
        $this->assertEquals($this->firstItem->getParentId(), $result['parent_id']);
        $this->assertTrue($this->firstItem->isVisible());
        $this->assertEquals($this->firstItem->getOrder(), $result['sort']);
        $this->assertEquals($this->firstItem->getType(), $result['type']);
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
        $this->assertEquals($this->firstItemData['order'], $item->getOrder());
        $this->assertEquals($this->firstItemData['header'], $item->getContent(Language::PL, Item::HEADER_1));
        $this->assertEquals($this->firstItemData['content'], $item->getContent(Language::EN, Item::CONTENT_1));
        $this->assertTrue($item->isVisible());
    }

    public function testGetFollowingOrder() {
        $followingOrder = Item::getFollowingOrder($this->testType);

        $this->assertEquals($followingOrder, count($this->items) * Orderable::ORDER_STEP);
    }

    public function testGetContentsByLanguage() {
        $polishContents = $this->firstItem->getContentsByLanguage(Language::PL);
        $englishContents = $this->firstItem->getContentsByLanguage(Language::EN);

        $this->assertTrue(array_key_exists(Item::HEADER_1, $polishContents));
        $this->assertEquals($this->firstItemData['header'], $polishContents[Item::HEADER_1]);

        $this->assertTrue(array_key_exists(Item::CONTENT_1, $englishContents));
        $this->assertEquals($this->firstItemData['content'], $englishContents[Item::CONTENT_1]);
    }

    /** COLLECTION **/

    public function testCollectionGetContentsByLanguage() {
        $polish = $this->itemsCollection->getContentsByLanguage(Language::PL);

        $this->assertEquals($polish[0]['id'], $this->items[0]->getId());
        $this->assertEquals($polish[1]['type'], $this->items[1]->getType());
        $this->assertEquals($polish[2]['order'], $this->items[2]->getOrder());
        $this->assertEquals($polish[3][Item::HEADER_1], $this->items[3]->getContent(Language::PL, Item::HEADER_1));
    }

    public function testCollectionAdd() {
        $this->assertEquals($this->items[2], $this->itemsCollection->get(2));
        $this->assertEquals($this->items[4], $this->itemsCollection->get(4));
    }

    public function testCollectionFactoryLoadAll() {
        $factory = new ItemsCollectionFactory(true);

        $collection = $factory->load($this->testType);

        $this->assertEquals($collection->get(1), $this->itemsCollection->get(1));
        $this->assertEquals($collection->get(4), $this->itemsCollection->get(4));
    }

    public function testCollectionFactoryLoadSelected() {
        $factory = new ItemsCollectionFactory(true);
        $factory->setOffset(1);
        $factory->setLimit(2);

        $collection = $factory->load($this->testType);

        $this->assertEquals($collection->get(0), $this->itemsCollection->get(1));
        $this->assertEquals($collection->get(1), $this->itemsCollection->get(2));
        $this->assertFalse($collection->get(2));
    }
}

?>