class CreateImages < ActiveRecord::Migration
  def self.up
    create_table :images do |t|
      t.integer :record_id
      t.string :record_type
      t.string :filename
      t.string :content_type
      t.integer :size
      t.integer :height
      t.integer :width

      t.timestamps
    end
  end

  def self.down
    drop_table :images
  end
end
