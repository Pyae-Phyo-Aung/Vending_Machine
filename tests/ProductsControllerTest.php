<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '../../controllers/ProductsController.php';

class ProductsControllerTest extends TestCase
{
  private $controller;
  private $stmt;
  private $pdo;

  protected function setUp(): void
  {
    $this->pdo = $this->createMock(PDO::class);
    $this->stmt = $this->createMock(PDOStatement::class);

    $refClass = new ReflectionClass(ProductsController::class);
    $this->controller = $refClass->newInstanceWithoutConstructor();

    $this->controller->setConnection($this->pdo);
  }

  public function testCreateInsertsProduct()
  {
    $reflection = new ReflectionClass($this->controller);
    $connProperty = $reflection->getProperty('conn');
    $connProperty->setAccessible(true);
    $this->assertSame($this->pdo, $connProperty->getValue($this->controller));

    $this->pdo->expects($this->once())
      ->method('prepare')
      ->with($this->stringContains('INSERT INTO products'))
      ->willReturn($this->stmt);

    $this->stmt->expects($this->once())
      ->method('execute')
      ->with(['Product A', 10.99, 5]);

    $this->controller->create('Product A', 10.99, 5);
  }

  public function testUpdateUpdatesProduct()
  {
    $reflection = new ReflectionClass($this->controller);
    $connProperty = $reflection->getProperty('conn');
    $connProperty->setAccessible(true);
    $this->assertSame($this->pdo, $connProperty->getValue($this->controller));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->with($this->stringContains('UPDATE products'))
      ->willReturn($this->stmt);

    $this->stmt->expects($this->once())
      ->method('execute')
      ->with(['Updated Name', 50.00, 10, 1]);

    $this->controller->update(1, 'Updated Name', 50.00, 10);
  }
}
