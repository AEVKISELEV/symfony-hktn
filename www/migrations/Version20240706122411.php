<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240706122411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_ai_post_analytic_generate_result DROP CONSTRAINT fk_b87509fb4b89032c');
        $this->addSql('DROP SEQUENCE app_group_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_post_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_comment_id_seq CASCADE');
        $this->addSql('CREATE TABLE "app_user" (vk_id INT NOT NULL, photo VARCHAR(255) DEFAULT NULL, firstname VARCHAR(180) NOT NULL, lastname VARCHAR(180) DEFAULT NULL, refresh_token TEXT DEFAULT NULL, access_token TEXT DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(vk_id))');
        $this->addSql('DROP TABLE app_post');
        $this->addSql('DROP TABLE app_comment');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE app_group');
        $this->addSql('DROP INDEX idx_b87509fb4b89032c');
        $this->addSql('ALTER TABLE app_ai_post_analytic_generate_result ADD vk_post_id TEXT NOT NULL');
        $this->addSql('ALTER TABLE app_ai_post_analytic_generate_result DROP post_id');
        $this->addSql('ALTER TABLE app_ai_post_analytic_generate_result ALTER content SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE app_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_post_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_post (id BIGINT NOT NULL, title VARCHAR(150) NOT NULL, author VARCHAR(150) NOT NULL, vk_id VARCHAR(150) NOT NULL, content VARCHAR(200) DEFAULT NULL, date_create TIMESTAMP(0) WITH TIME ZONE NOT NULL, likes_amount BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_5fa4492dc5978e52 ON app_post (vk_id)');
        $this->addSql('COMMENT ON COLUMN app_post.date_create IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE app_comment (id BIGINT NOT NULL, author VARCHAR(150) NOT NULL, content VARCHAR(200) DEFAULT NULL, date_create TIMESTAMP(0) WITH TIME ZONE NOT NULL, likes_amount BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN app_comment.date_create IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE "user" (vk_id INT NOT NULL, photo VARCHAR(255) DEFAULT NULL, firstname VARCHAR(180) NOT NULL, lastname VARCHAR(180) DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, refresh_token TEXT DEFAULT NULL, access_token TEXT DEFAULT NULL, PRIMARY KEY(vk_id))');
        $this->addSql('CREATE TABLE app_group (id BIGINT NOT NULL, title VARCHAR(50) NOT NULL, vk_id VARCHAR(150) NOT NULL, description VARCHAR(200) DEFAULT NULL, create_date_time TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_bb13c908c5978e52 ON app_group (vk_id)');
        $this->addSql('COMMENT ON COLUMN app_group.create_date_time IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('DROP TABLE "app_user"');
        $this->addSql('ALTER TABLE "app_ai_post_analytic_generate_result" ADD post_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE "app_ai_post_analytic_generate_result" DROP vk_post_id');
        $this->addSql('ALTER TABLE "app_ai_post_analytic_generate_result" ALTER content DROP NOT NULL');
        $this->addSql('ALTER TABLE "app_ai_post_analytic_generate_result" ADD CONSTRAINT fk_b87509fb4b89032c FOREIGN KEY (post_id) REFERENCES app_post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b87509fb4b89032c ON "app_ai_post_analytic_generate_result" (post_id)');
    }
}
