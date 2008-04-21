class CreateReleases < ActiveRecord::Migration
  def self.up
    create_table :releases do |t|
      t.integer :movie_id
      t.string :format
      t.date :released_on

      t.timestamps
    end
  end

  def self.down
    drop_table :releases
  end
end
