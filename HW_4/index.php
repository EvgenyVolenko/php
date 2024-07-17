<?php

interface ActionWithBook
{
    public function getBookForRead(): string;
}

abstract class AbstractBook implements ActionWithBook
{
    protected string $name;
    protected array $authors;
    protected int $shelfId;
    protected int $readStat = 0;

    public function __construct(
        string $name,
        array $authors,
        int $shelfId = 0,
        int $readStat = 0
    ) {
        $this->name = $name;
        $this->authors = $authors;
        $this->shelfId = $shelfId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getReadStat()
    {
        return $this->readStat;
    }
}

class Book extends AbstractBook
{
    public function getBookForRead(): string
    {
        $this->readStat++;
        return $this->shelfId;
    }
}

class DigitalBook extends Book
{
    private string $link;

    public function __construct(
        string $name,
        array $authors,
        string $link,
    ) {
        parent::__construct($name, $authors);
        $this->link = $link;
    }

    public function getBookForRead(): string
    {
        $this->readStat++;
        return $this->link;
    }
}

class Shelf
{
    private int $shelfId;
    private int $roomId;
    private array $volume;
    private int $volumeMax;

    public function __construct(
        int $shelfId,
        int $roomId,
        int $volumeMax,
        array $volume = []
    ) {
        $this->shelfId = $shelfId;
        $this->roomId = $roomId;
        $this->volumeMax = $volumeMax;
        $this->volume = $volume;
    }

    public function addBook(Book $book): string
    {
        if (count($this->volume) < $this->volumeMax) {
            $this->volume[] = $book;
            return "Книга {$book->getName()} добавлена на полку";
        } else {
            return "На полке больше нет места";
        }
    }

    public function getShelfId(): int
    {
        return $this->shelfId;
    }
}

class Room
{
    private int $roomId;
    private string $address;
    private array $shelfS;

    public function __construct(int $roomId, string $address, int $volumeMax, array $shelfS = [])
    {
        $this->roomId = $roomId;
        $this->address = $address;
        $this->shelfS[] = new Shelf(count($shelfS) + 1, $this->roomId, $volumeMax);
    }

    public function getShelf(int $shelfId): Shelf | string
    {
        foreach ($this->shelfS as $key => $value) {
            if ($shelfId === $value->getShelfId()) {
                return $value;
            }
        }
        return 'Нет такой полки';
    }

    public function getBookByName($name): string
    {
        foreach ($this->shelfS as $key => $value) {
            if ($value->getName() === $name) {
                return $this->address;
            }
        }
        return 'Нет такой книги';
    }
}

$room = new Room(1, 'Moscow', 3);

$book = new Book('Три мушкетера', ['Дюма А.'], 15);
$digBook = new DigitalBook('Война и мир', ['Толстой Л.Н.'], 'link');

print_r($room->getShelf(1)->addBook($book) . PHP_EOL);
$room->getShelf(1)->addBook($book);
$room->getShelf(1)->addBook($book);
print_r($room->getShelf(1)->addBook($book) . PHP_EOL);
print_r($room->getShelf(1)->addBook($book) . PHP_EOL);
print_r($digBook->getBookForRead() . PHP_EOL);
$digBook->getBookForRead();
print_r($digBook->getReadStat());
