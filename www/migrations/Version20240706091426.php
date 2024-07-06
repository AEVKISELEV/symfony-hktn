<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240706091426 extends AbstractMigration
{
	public function getDescription(): string
	{
		return '';
	}

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql('CREATE SEQUENCE "app_group_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
		$this->addSql(
			'CREATE TABLE "app_group" (id BIGINT NOT NULL, title VARCHAR(50) NOT NULL, vk_id VARCHAR(150) NOT NULL, description VARCHAR(200) DEFAULT NULL, create_date_time TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))',
		);
		$this->addSql('CREATE UNIQUE INDEX UNIQ_BB13C908C5978E52 ON "app_group" (vk_id)');
		$this->addSql('COMMENT ON COLUMN "app_group".create_date_time IS \'(DC2Type:datetimetz_immutable)\'');
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->addSql('CREATE SCHEMA public');
		$this->addSql('DROP SEQUENCE "app_group_id_seq" CASCADE');
		$this->addSql('DROP TABLE "app_group"');
	}
}
