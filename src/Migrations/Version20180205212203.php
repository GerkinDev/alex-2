<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180205212203 extends AbstractMigration
{
	public function up(Schema $schema) {
		// this up() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('CREATE TABLE model (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, attributes_id INT DEFAULT NULL, model VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, public TINYINT(1) NOT NULL, base_price DOUBLE PRECISION NOT NULL, INDEX IDX_D79572D9A76ED395 (user_id), INDEX IDX_D79572D9BAAF4009 (attributes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
		$this->addSql('CREATE TABLE printing (id INT AUTO_INCREMENT NOT NULL, model_id INT NOT NULL, status SMALLINT NOT NULL, UNIQUE INDEX UNIQ_308F6DF37975B7E7 (model_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
		$this->addSql('CREATE TABLE product_attribute (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, factor INT NOT NULL, UNIQUE INDEX UNIQ_94DA597612469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
		$this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, fname VARCHAR(255) NOT NULL, lname VARCHAR(255) NOT NULL, password VARCHAR(64) NOT NULL, password_reset_token VARCHAR(64) DEFAULT NULL, salt VARCHAR(255) NOT NULL, email VARCHAR(60) NOT NULL, role VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
		$this->addSql('CREATE TABLE variable_attribute (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, available TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_1F0C831212469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
		$this->addSql('CREATE TABLE variable_attribute_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
		$this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
		$this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D9BAAF4009 FOREIGN KEY (attributes_id) REFERENCES product_attribute (id)');
		$this->addSql('ALTER TABLE printing ADD CONSTRAINT FK_308F6DF37975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
		$this->addSql('ALTER TABLE product_attribute ADD CONSTRAINT FK_94DA597612469DE2 FOREIGN KEY (category_id) REFERENCES variable_attribute_category (id)');
		$this->addSql('ALTER TABLE variable_attribute ADD CONSTRAINT FK_1F0C831212469DE2 FOREIGN KEY (category_id) REFERENCES variable_attribute_category (id)');
	}

	public function down(Schema $schema) {
		// this down() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('ALTER TABLE printing DROP FOREIGN KEY FK_308F6DF37975B7E7');
		$this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D9BAAF4009');
		$this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D9A76ED395');
		$this->addSql('ALTER TABLE product_attribute DROP FOREIGN KEY FK_94DA597612469DE2');
		$this->addSql('ALTER TABLE variable_attribute DROP FOREIGN KEY FK_1F0C831212469DE2');
		$this->addSql('DROP TABLE model');
		$this->addSql('DROP TABLE printing');
		$this->addSql('DROP TABLE product_attribute');
		$this->addSql('DROP TABLE user');
		$this->addSql('DROP TABLE variable_attribute');
		$this->addSql('DROP TABLE variable_attribute_category');
	}
}
