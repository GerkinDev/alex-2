<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180205214153 extends AbstractMigration
{
	public function up(Schema $schema) {
		// this up() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D9BAAF4009');
		$this->addSql('DROP INDEX IDX_D79572D9BAAF4009 ON model');
		$this->addSql('ALTER TABLE model DROP attributes_id');
		$this->addSql('ALTER TABLE product_attribute ADD model_id INT DEFAULT NULL, CHANGE factor factor DOUBLE PRECISION NOT NULL');
		$this->addSql('ALTER TABLE product_attribute ADD CONSTRAINT FK_94DA59767975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
		$this->addSql('CREATE INDEX IDX_94DA59767975B7E7 ON product_attribute (model_id)');
	}

	public function down(Schema $schema) {
		// this down() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('ALTER TABLE model ADD attributes_id INT DEFAULT NULL');
		$this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D9BAAF4009 FOREIGN KEY (attributes_id) REFERENCES product_attribute (id)');
		$this->addSql('CREATE INDEX IDX_D79572D9BAAF4009 ON model (attributes_id)');
		$this->addSql('ALTER TABLE product_attribute DROP FOREIGN KEY FK_94DA59767975B7E7');
		$this->addSql('DROP INDEX IDX_94DA59767975B7E7 ON product_attribute');
		$this->addSql('ALTER TABLE product_attribute DROP model_id, CHANGE factor factor INT NOT NULL');
	}
}
