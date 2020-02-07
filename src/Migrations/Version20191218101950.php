<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191218101950 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Action (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_406089A45E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Customer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Icon (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, class VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C5A686E55E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Permission (id INT AUTO_INCREMENT NOT NULL, action_id INT DEFAULT NULL, subject_id INT DEFAULT NULL, tool_id INT DEFAULT NULL, INDEX IDX_AF14917A9D32F035 (action_id), INDEX IDX_AF14917A23EDC87 (subject_id), INDEX IDX_AF14917A8F7B22CC (tool_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission_stage (permission_id INT NOT NULL, stage_id INT NOT NULL, INDEX IDX_14FBFE1AFED90CCA (permission_id), INDEX IDX_14FBFE1A2298D193 (stage_id), PRIMARY KEY(permission_id, stage_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_F75B25545E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_permission (role_id INT NOT NULL, permission_id INT NOT NULL, INDEX IDX_6F7DF886D60322AC (role_id), INDEX IDX_6F7DF886FED90CCA (permission_id), PRIMARY KEY(role_id, permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Stage (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3BDBC6D5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage_role (stage_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_95BC85FF2298D193 (stage_id), INDEX IDX_95BC85FFD60322AC (role_id), PRIMARY KEY(stage_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Subject (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_347307E65E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_CF8E3B185E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Template (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, orientation VARCHAR(1) NOT NULL, create_at DATETIME NOT NULL, last_modification_date DATETIME DEFAULT NULL, height VARCHAR(255) NOT NULL, width VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, background INT DEFAULT NULL, UNIQUE INDEX UNIQ_6E167DD55E237E06 (name), INDEX IDX_6E167DD59395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template_tags (template_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_BA72BE4B5DA0FB8 (template_id), INDEX IDX_BA72BE4B8D7B4FB4 (tags_id), PRIMARY KEY(template_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Tool (id INT AUTO_INCREMENT NOT NULL, icon_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, data_attribute VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_80C191EF5E237E06 (name), INDEX IDX_80C191EF54B9D732 (icon_id), INDEX IDX_80C191EF727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE toolbox (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, isShortcut TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_E193AFC65E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE toolbox_tool (toolbox_id INT NOT NULL, tool_id INT NOT NULL, INDEX IDX_B6BB9EE6B3FA4DFB (toolbox_id), INDEX IDX_B6BB9EE68F7B22CC (tool_id), PRIMARY KEY(toolbox_id, tool_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tool_box_stage (toolbox_id INT NOT NULL, stage_id INT NOT NULL, INDEX IDX_1EB33B9EB3FA4DFB (toolbox_id), INDEX IDX_1EB33B9E2298D193 (stage_id), PRIMARY KEY(toolbox_id, stage_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE User (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, customer_id INT NOT NULL, username VARCHAR(255) NOT NULL, user_password VARCHAR(255) NOT NULL, password_reset_token VARCHAR(255) DEFAULT NULL, password_reset_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_2DA17977F85E0677 (username), UNIQUE INDEX UNIQ_2DA17977D54FA2D5 (user_password), INDEX IDX_2DA17977D60322AC (role_id), INDEX IDX_2DA179779395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_permission (user_id INT NOT NULL, permission_id INT NOT NULL, INDEX IDX_472E5446A76ED395 (user_id), INDEX IDX_472E5446FED90CCA (permission_id), PRIMARY KEY(user_id, permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Zone (id INT AUTO_INCREMENT NOT NULL, template_id INT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, isBlocked TINYINT(1) NOT NULL, background INT NOT NULL, width VARCHAR(255) NOT NULL, height VARCHAR(255) NOT NULL, position_top VARCHAR(255) NOT NULL, position_left VARCHAR(255) NOT NULL, z_index VARCHAR(255) NOT NULL, position VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D96F395E237E06 (name), INDEX IDX_D96F395DA0FB8 (template_id), INDEX IDX_D96F39727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Permission ADD CONSTRAINT FK_AF14917A9D32F035 FOREIGN KEY (action_id) REFERENCES Action (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Permission ADD CONSTRAINT FK_AF14917A23EDC87 FOREIGN KEY (subject_id) REFERENCES Subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Permission ADD CONSTRAINT FK_AF14917A8F7B22CC FOREIGN KEY (tool_id) REFERENCES Tool (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permission_stage ADD CONSTRAINT FK_14FBFE1AFED90CCA FOREIGN KEY (permission_id) REFERENCES Permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permission_stage ADD CONSTRAINT FK_14FBFE1A2298D193 FOREIGN KEY (stage_id) REFERENCES Stage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_permission ADD CONSTRAINT FK_6F7DF886D60322AC FOREIGN KEY (role_id) REFERENCES Role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_permission ADD CONSTRAINT FK_6F7DF886FED90CCA FOREIGN KEY (permission_id) REFERENCES Permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stage_role ADD CONSTRAINT FK_95BC85FF2298D193 FOREIGN KEY (stage_id) REFERENCES Stage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stage_role ADD CONSTRAINT FK_95BC85FFD60322AC FOREIGN KEY (role_id) REFERENCES Role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Template ADD CONSTRAINT FK_6E167DD59395C3F3 FOREIGN KEY (customer_id) REFERENCES Customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_tags ADD CONSTRAINT FK_BA72BE4B5DA0FB8 FOREIGN KEY (template_id) REFERENCES Template (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_tags ADD CONSTRAINT FK_BA72BE4B8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES Tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Tool ADD CONSTRAINT FK_80C191EF54B9D732 FOREIGN KEY (icon_id) REFERENCES Icon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Tool ADD CONSTRAINT FK_80C191EF727ACA70 FOREIGN KEY (parent_id) REFERENCES Tool (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE toolbox_tool ADD CONSTRAINT FK_B6BB9EE6B3FA4DFB FOREIGN KEY (toolbox_id) REFERENCES toolbox (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE toolbox_tool ADD CONSTRAINT FK_B6BB9EE68F7B22CC FOREIGN KEY (tool_id) REFERENCES Tool (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tool_box_stage ADD CONSTRAINT FK_1EB33B9EB3FA4DFB FOREIGN KEY (toolbox_id) REFERENCES toolbox (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tool_box_stage ADD CONSTRAINT FK_1EB33B9E2298D193 FOREIGN KEY (stage_id) REFERENCES Stage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_2DA17977D60322AC FOREIGN KEY (role_id) REFERENCES Role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_2DA179779395C3F3 FOREIGN KEY (customer_id) REFERENCES Customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_permission ADD CONSTRAINT FK_472E5446A76ED395 FOREIGN KEY (user_id) REFERENCES User (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_permission ADD CONSTRAINT FK_472E5446FED90CCA FOREIGN KEY (permission_id) REFERENCES Permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Zone ADD CONSTRAINT FK_D96F395DA0FB8 FOREIGN KEY (template_id) REFERENCES Template (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Zone ADD CONSTRAINT FK_D96F39727ACA70 FOREIGN KEY (parent_id) REFERENCES Zone (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Permission DROP FOREIGN KEY FK_AF14917A9D32F035');
        $this->addSql('ALTER TABLE Template DROP FOREIGN KEY FK_6E167DD59395C3F3');
        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_2DA179779395C3F3');
        $this->addSql('ALTER TABLE Tool DROP FOREIGN KEY FK_80C191EF54B9D732');
        $this->addSql('ALTER TABLE permission_stage DROP FOREIGN KEY FK_14FBFE1AFED90CCA');
        $this->addSql('ALTER TABLE role_permission DROP FOREIGN KEY FK_6F7DF886FED90CCA');
        $this->addSql('ALTER TABLE user_permission DROP FOREIGN KEY FK_472E5446FED90CCA');
        $this->addSql('ALTER TABLE role_permission DROP FOREIGN KEY FK_6F7DF886D60322AC');
        $this->addSql('ALTER TABLE stage_role DROP FOREIGN KEY FK_95BC85FFD60322AC');
        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_2DA17977D60322AC');
        $this->addSql('ALTER TABLE permission_stage DROP FOREIGN KEY FK_14FBFE1A2298D193');
        $this->addSql('ALTER TABLE stage_role DROP FOREIGN KEY FK_95BC85FF2298D193');
        $this->addSql('ALTER TABLE tool_box_stage DROP FOREIGN KEY FK_1EB33B9E2298D193');
        $this->addSql('ALTER TABLE Permission DROP FOREIGN KEY FK_AF14917A23EDC87');
        $this->addSql('ALTER TABLE template_tags DROP FOREIGN KEY FK_BA72BE4B8D7B4FB4');
        $this->addSql('ALTER TABLE template_tags DROP FOREIGN KEY FK_BA72BE4B5DA0FB8');
        $this->addSql('ALTER TABLE Zone DROP FOREIGN KEY FK_D96F395DA0FB8');
        $this->addSql('ALTER TABLE Permission DROP FOREIGN KEY FK_AF14917A8F7B22CC');
        $this->addSql('ALTER TABLE Tool DROP FOREIGN KEY FK_80C191EF727ACA70');
        $this->addSql('ALTER TABLE toolbox_tool DROP FOREIGN KEY FK_B6BB9EE68F7B22CC');
        $this->addSql('ALTER TABLE toolbox_tool DROP FOREIGN KEY FK_B6BB9EE6B3FA4DFB');
        $this->addSql('ALTER TABLE tool_box_stage DROP FOREIGN KEY FK_1EB33B9EB3FA4DFB');
        $this->addSql('ALTER TABLE user_permission DROP FOREIGN KEY FK_472E5446A76ED395');
        $this->addSql('ALTER TABLE Zone DROP FOREIGN KEY FK_D96F39727ACA70');
        $this->addSql('DROP TABLE Action');
        $this->addSql('DROP TABLE Customer');
        $this->addSql('DROP TABLE Icon');
        $this->addSql('DROP TABLE Permission');
        $this->addSql('DROP TABLE permission_stage');
        $this->addSql('DROP TABLE Role');
        $this->addSql('DROP TABLE role_permission');
        $this->addSql('DROP TABLE Stage');
        $this->addSql('DROP TABLE stage_role');
        $this->addSql('DROP TABLE Subject');
        $this->addSql('DROP TABLE Tags');
        $this->addSql('DROP TABLE Template');
        $this->addSql('DROP TABLE template_tags');
        $this->addSql('DROP TABLE Tool');
        $this->addSql('DROP TABLE toolbox');
        $this->addSql('DROP TABLE toolbox_tool');
        $this->addSql('DROP TABLE tool_box_stage');
        $this->addSql('DROP TABLE User');
        $this->addSql('DROP TABLE user_permission');
        $this->addSql('DROP TABLE Zone');
    }
}
