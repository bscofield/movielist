class CreateAudits < ActiveRecord::Migration
  def self.up
    create_table :audits do |t|
      t.integer :record_id
      t.string :record_type
      t.string :event

      t.timestamps
    end
  end

  def self.down
    drop_table :audits
  end
end
