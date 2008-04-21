class CreateInterests < ActiveRecord::Migration
  def self.up
    create_table :interests do |t|
      t.integer :facebook_id, :null => false
      t.integer :movie_id, :null => false

      t.timestamps
    end
    
    execute("ALTER TABLE interests MODIFY facebook_id BIGINT") # MySQL-specific
  end

  def self.down
    drop_table :interests
  end
end
