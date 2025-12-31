<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251231105858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gambar_hotel (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) NOT NULL, hotel_id INT NOT NULL, INDEX IDX_BFD65E333243BB18 (hotel_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE gambar_kamar (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) NOT NULL, tipe_kamar_id INT NOT NULL, INDEX IDX_953D33484C8BD899 (tipe_kamar_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE hotel (id INT AUTO_INCREMENT NOT NULL, nama_hotel VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, alamat VARCHAR(255) NOT NULL, kontak VARCHAR(255) NOT NULL, deskripsi VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE pembatalan (id INT AUTO_INCREMENT NOT NULL, alasan VARCHAR(255) NOT NULL, tanggal_pengajuan DATE NOT NULL, catatan_admin VARCHAR(255) NOT NULL, tanggal_refund DATE NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE pembayaran (id INT AUTO_INCREMENT NOT NULL, total_harga INT NOT NULL, tipe_pembayaran VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE reservasi (id INT AUTO_INCREMENT NOT NULL, tanggal_reservasi DATE NOT NULL, tanggal_check_in DATE NOT NULL, tanggal_check_out DATE NOT NULL, jumlah_kamar INT NOT NULL, total_malam INT NOT NULL, user_id INT NOT NULL, kamar_id INT NOT NULL, pembayaran_id INT DEFAULT NULL, pembatalan_id INT DEFAULT NULL, INDEX IDX_91C11B4CA76ED395 (user_id), INDEX IDX_91C11B4C8D6C1299 (kamar_id), INDEX IDX_91C11B4C3EA41844 (pembayaran_id), INDEX IDX_91C11B4C9C7D6E7E (pembatalan_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, gambar VARCHAR(255) NOT NULL, rating SMALLINT NOT NULL, deskripsi VARCHAR(255) NOT NULL, user_id INT NOT NULL, INDEX IDX_794381C6A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tipe_kamar (id INT AUTO_INCREMENT NOT NULL, nama_kamar VARCHAR(64) NOT NULL, deskripsi VARCHAR(255) NOT NULL, kapasitas_orang INT NOT NULL, total_kamar INT NOT NULL, harga INT NOT NULL, hotel_id INT NOT NULL, INDEX IDX_8B74F6243243BB18 (hotel_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nama VARCHAR(64) NOT NULL, no_telepon VARCHAR(16) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE gambar_hotel ADD CONSTRAINT FK_BFD65E333243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE gambar_kamar ADD CONSTRAINT FK_953D33484C8BD899 FOREIGN KEY (tipe_kamar_id) REFERENCES tipe_kamar (id)');
        $this->addSql('ALTER TABLE reservasi ADD CONSTRAINT FK_91C11B4CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservasi ADD CONSTRAINT FK_91C11B4C8D6C1299 FOREIGN KEY (kamar_id) REFERENCES tipe_kamar (id)');
        $this->addSql('ALTER TABLE reservasi ADD CONSTRAINT FK_91C11B4C3EA41844 FOREIGN KEY (pembayaran_id) REFERENCES pembayaran (id)');
        $this->addSql('ALTER TABLE reservasi ADD CONSTRAINT FK_91C11B4C9C7D6E7E FOREIGN KEY (pembatalan_id) REFERENCES pembatalan (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tipe_kamar ADD CONSTRAINT FK_8B74F6243243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gambar_hotel DROP FOREIGN KEY FK_BFD65E333243BB18');
        $this->addSql('ALTER TABLE gambar_kamar DROP FOREIGN KEY FK_953D33484C8BD899');
        $this->addSql('ALTER TABLE reservasi DROP FOREIGN KEY FK_91C11B4CA76ED395');
        $this->addSql('ALTER TABLE reservasi DROP FOREIGN KEY FK_91C11B4C8D6C1299');
        $this->addSql('ALTER TABLE reservasi DROP FOREIGN KEY FK_91C11B4C3EA41844');
        $this->addSql('ALTER TABLE reservasi DROP FOREIGN KEY FK_91C11B4C9C7D6E7E');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE tipe_kamar DROP FOREIGN KEY FK_8B74F6243243BB18');
        $this->addSql('DROP TABLE gambar_hotel');
        $this->addSql('DROP TABLE gambar_kamar');
        $this->addSql('DROP TABLE hotel');
        $this->addSql('DROP TABLE pembatalan');
        $this->addSql('DROP TABLE pembayaran');
        $this->addSql('DROP TABLE reservasi');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE tipe_kamar');
        $this->addSql('DROP TABLE user');
    }
}
