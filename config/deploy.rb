require 'capistrano/ext/multistage'
set :stages, ['staging', 'production']
set :application, "preworn_www"
set :repository,  "git@github.com:JackSzwergold/ImageMosaic-Class.git"
set :git_enable_submodules, true

set :scm, :git
set :use_sudo, false
set :keep_releases, 3
ssh_options[:forward_agent] = true

set :web_root, "/var/www"
set :deployment_root, "#{web_root}"
set :media_dir, "/var/www/www.preworn.com/site"

namespace :deploy do
  task :restart do
    #nothing
  end
  task :create_release_dir, :except => {:no_release => true} do
    run "mkdir -p #{fetch :releases_path}"
  end
end

# Clean up the stray symlinks: current, log, public & tmp
task :delete_extra_symlink do
  # Get rid of Ruby specific 'current' & 'log' symlinks.
  run "cd #{current_path} && if [ -h current ]; then rm current; fi && if [ -h log ]; then rm log; fi"
  # Get rid of Ruby specific 'public' directory.
  run "cd #{current_path} && if [ -d public ]; then rm -rf public; fi && if [ -d public ]; then rm -rf public; fi"
  # Get rid of the 'sundry' directory.
  run "cd #{current_path} && if [ -d sundry ]; then rm -rf sundry; fi && if [ -d sundry ]; then rm -rf sundry; fi"
end

# Delete capistrano config files from release
task :delete_cap_files do
  run "cd #{current_release} && rm Capfile && rm -rf config && if [ -f README.md ]; then rm README.md; fi"
  run "cd #{current_release} && rm Capfile && rm -rf config && if [ -f README.md ]; then rm README.md; fi"
end

# Echo the current path to a file.
task :echo_current_path do
  run "echo #{current_release} > #{current_release}/CURRENT_PATH"
end

before "deploy:update", "deploy:create_release_dir"
before "deploy:create_symlink", :delete_cap_files
after "deploy:update", :delete_extra_symlink
after "deploy:update", :echo_current_path