<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180205184723 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE attribute (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, available TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_FA7AEFFB12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attribute_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_attribute (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, factor INT NOT NULL, UNIQUE INDEX UNIQ_94DA597612469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_attribute_model (product_attribute_id INT NOT NULL, model_id INT NOT NULL, INDEX IDX_62694F23B420C91 (product_attribute_id), INDEX IDX_62694F27975B7E7 (model_id), PRIMARY KEY(product_attribute_id, model_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attribute ADD CONSTRAINT FK_FA7AEFFB12469DE2 FOREIGN KEY (category_id) REFERENCES attribute_category (id)');
        $this->addSql('ALTER TABLE product_attribute ADD CONSTRAINT FK_94DA597612469DE2 FOREIGN KEY (category_id) REFERENCES attribute_category (id)');
        $this->addSql('ALTER TABLE product_attribute_model ADD CONSTRAINT FK_62694F23B420C91 FOREIGN KEY (product_attribute_id) REFERENCES product_attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_attribute_model ADD CONSTRAINT FK_62694F27975B7E7 FOREIGN KEY (model_id) REFERENCES model (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE material');
        $this->addSql('ALTER TABLE model DROP masses');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE attribute DROP FOREIGN KEY FK_FA7AEFFB12469DE2');
        $this->addSql('ALTER TABLE product_attribute DROP FOREIGN KEY FK_94DA597612469DE2');
        $this->addSql('ALTER TABLE product_attribute_model DROP FOREIGN KEY FK_62694F23B420C91');
        $this->addSql('CREATE TABLE material (id INT AUTO_INCREMENT NOT NULL, price DOUBLE PRECISION NOT NULL, available TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE attribute');
        $this->addSql('DROP TABLE attribute_category');
        $this->addSql('DROP TABLE product_attribute');
        $this->addSql('DROP TABLE product_attribute_model');
        $this->addSql('ALTER TABLE model ADD masses VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
