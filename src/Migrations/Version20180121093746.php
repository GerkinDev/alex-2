<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180121093746 extends AbstractMigration
{
	public function up(Schema $schema) {
		// this up() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('ALTER TABLE model CHANGE model model VARCHAR(255) DEFAULT NULL, CHANGE price price VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL');
	}

	public function down(Schema $schema) {
		// this down() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('ALTER TABLE model CHANGE model model VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE image image VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE price price JSON NOT NULL');
	}
}
