<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241010111559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE anime (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) DEFAULT NULL, score DOUBLE PRECISION DEFAULT NULL, genre VARCHAR(125) DEFAULT NULL, english_name VARCHAR(114) DEFAULT NULL, synopsis LONGTEXT DEFAULT NULL, type VARCHAR(10) DEFAULT NULL, episode INT DEFAULT NULL, aired VARCHAR(28) DEFAULT NULL, premiered VARCHAR(11) DEFAULT NULL, producers VARCHAR(375) DEFAULT NULL, licensors VARCHAR(69) DEFAULT NULL, studios VARCHAR(80) DEFAULT NULL, source VARCHAR(13) DEFAULT NULL, duration VARCHAR(21) DEFAULT NULL, rating VARCHAR(30) DEFAULT NULL, ranked INT DEFAULT NULL, popularity INT DEFAULT NULL, members INT DEFAULT NULL, favorites INT DEFAULT NULL, watching INT DEFAULT NULL, completed INT DEFAULT NULL, on_hold INT DEFAULT NULL, droped INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rate (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, anime_id_id INT DEFAULT NULL, rating INT NOT NULL, INDEX IDX_DFEC3F399D86650F (user_id_id), INDEX IDX_DFEC3F3913EDFE68 (anime_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(45) DEFAULT NULL, gender VARCHAR(45) DEFAULT NULL, birthday VARCHAR(45) DEFAULT NULL, location VARCHAR(45) DEFAULT NULL, joined VARCHAR(45) DEFAULT NULL, days_watched INT DEFAULT NULL, mean_score INT DEFAULT NULL, watching INT DEFAULT NULL, completed INT DEFAULT NULL, on_hold INT DEFAULT NULL, dropped INT DEFAULT NULL, plan_to_watch INT DEFAULT NULL, total_entries INT DEFAULT NULL, rewatched INT DEFAULT NULL, episodes INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F399D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F3913EDFE68 FOREIGN KEY (anime_id_id) REFERENCES anime (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rate DROP FOREIGN KEY FK_DFEC3F399D86650F');
        $this->addSql('ALTER TABLE rate DROP FOREIGN KEY FK_DFEC3F3913EDFE68');
        $this->addSql('DROP TABLE anime');
        $this->addSql('DROP TABLE rate');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
