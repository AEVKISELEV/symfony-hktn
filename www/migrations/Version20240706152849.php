<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240706152849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_ai_post_analytic_generate_result ADD vk_group_id TEXT NOT NULL');
        $this->addSql('ALTER TABLE app_ai_post_analytic_generate_result ADD type TEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "app_ai_post_analytic_generate_result" DROP vk_group_id');
        $this->addSql('ALTER TABLE "app_ai_post_analytic_generate_result" DROP type');
    }
}
