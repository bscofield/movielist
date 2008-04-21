class AddFacebookIdToUserModel < ActiveRecord::Migration
  def self.up
    add_column :users, :facebook_id, :integer, :null => true
    
    execute("ALTER TABLE users MODIFY facebook_id BIGINT") # MySQL-specific
  end

  def self.down
    remove_column :users, :facebook_id
  end
end
