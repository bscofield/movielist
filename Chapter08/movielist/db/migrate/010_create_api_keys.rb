class CreateApiKeys < ActiveRecord::Migration
  def self.up
    create_table :api_keys do |t|
      t.integer :user_id
      t.string :identifier

      t.timestamps
    end
  end

  def self.down
    drop_table :api_keys
  end
end
