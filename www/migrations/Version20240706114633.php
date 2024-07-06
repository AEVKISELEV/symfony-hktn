<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240706114633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "app_ai_post_analytic_generate_result_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "app_ai_post_analytic_generate_result" (id BIGINT NOT NULL, post_id BIGINT NOT NULL, content VARCHAR(200) DEFAULT NULL, date_create TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B87509FB4B89032C ON "app_ai_post_analytic_generate_result" (post_id)');
        $this->addSql('COMMENT ON COLUMN "app_ai_post_analytic_generate_result".date_create IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE "app_ai_post_analytic_generate_result" ADD CONSTRAINT FK_B87509FB4B89032C FOREIGN KEY (post_id) REFERENCES "app_post" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ALTER refresh_token TYPE TEXT');
        $this->addSql('ALTER TABLE "user" ALTER refresh_token TYPE TEXT');
        $this->addSql('ALTER TABLE "user" ALTER access_token TYPE TEXT');
        $this->addSql('ALTER TABLE "user" ALTER access_token TYPE TEXT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "app_ai_post_analytic_generate_result_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "app_ai_post_analytic_generate_result" DROP CONSTRAINT FK_B87509FB4B89032C');
        $this->addSql('DROP TABLE "app_ai_post_analytic_generate_result"');
        $this->addSql('ALTER TABLE "user" ALTER refresh_token TYPE VARCHAR(180)');
        $this->addSql('ALTER TABLE "user" ALTER access_token TYPE VARCHAR(180)');
    }
}
