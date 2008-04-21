class CreateKeyAccesses < ActiveRecord::Migration
  def self.up
    create_table :key_accesses do |t|
      t.integer :api_key_id
      t.datetime :used_at

      t.timestamps
    end
  end

  def self.down
    drop_table :key_accesses
  end
end
