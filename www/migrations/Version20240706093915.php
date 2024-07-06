<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240706093915 extends AbstractMigration
{
	public function getDescription(): string
	{
		return '';
	}

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql('CREATE SEQUENCE "app_post_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
		$this->addSql(
			'CREATE TABLE "app_post" (id BIGINT NOT NULL, title VARCHAR(150) NOT NULL, author VARCHAR(150) NOT NULL, vk_id VARCHAR(150) NOT NULL, content VARCHAR(200) DEFAULT NULL, date_create TIMESTAMP(0) WITH TIME ZONE NOT NULL, likes_amount BIGINT NOT NULL, PRIMARY KEY(id))',
		);
		$this->addSql('CREATE UNIQUE INDEX UNIQ_5FA4492DC5978E52 ON "app_post" (vk_id)');
		$this->addSql('COMMENT ON COLUMN "app_post".date_create IS \'(DC2Type:datetimetz_immutable)\'');
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->addSql('CREATE SCHEMA public');
		$this->addSql('DROP SEQUENCE "app_post_id_seq" CASCADE');
		$this->addSql('DROP TABLE "app_post"');
	}
}
