-- Data in user table
ALTER TABLE wcf1_user ADD uzAttachments INT(10) NOT NULL DEFAULT 0;

-- Attachment
DROP TABLE IF EXISTS wcf1_attachment_log;
CREATE TABLE wcf1_attachment_log (
	logID				INT(10) AUTO_INCREMENT PRIMARY KEY,
	attachmentID		INT(10),
	filename			VARCHAR(255) NOT NULL DEFAULT '',
	fileType			VARCHAR(255) NOT NULL DEFAULT '',
	objectType			VARCHAR(191),
	time				INT(10),
	private				TINYINT(1) NOT NULL DEFAULT 0,
	userID				INT(10),
	username			VARCHAR(255)
);

ALTER TABLE wcf1_attachment_log ADD FOREIGN KEY (attachmentID) REFERENCES wcf1_attachment (attachmentID) ON DELETE CASCADE;
ALTER TABLE wcf1_attachment_log ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE SET NULL;
