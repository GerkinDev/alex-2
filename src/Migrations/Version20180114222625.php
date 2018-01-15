<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180114222625 extends AbstractMigration
{
	public function up(Schema $schema) {
		// this up() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
		$this->addSql('ALTER TABLE user ADD fname VARCHAR(255) NOT NULL, ADD lname VARCHAR(255) NOT NULL, DROP username');
	}

	public function down(Schema $schema) {
		// this down() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('ALTER TABLE user ADD username VARCHAR(25) NOT NULL COLLATE utf8_unicode_ci, DROP fname, DROP lname');
		$this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
	}
}
