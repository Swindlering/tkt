<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210626040432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (
                id INT AUTO_INCREMENT NOT NULL, 
                user_id INT NOT NULL, 
                title VARCHAR(255) NOT NULL, 
                subtitle VARCHAR(255) DEFAULT NULL, 
                content VARCHAR(255) NOT NULL, 
                updated DATETIME DEFAULT NULL, 
                created DATETIME NOT NULL, 
                UNIQUE INDEX UNIQ_23A0E662B36786B (title), 
                INDEX IDX_23A0E66A76ED395 (user_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE article');
    }
}
