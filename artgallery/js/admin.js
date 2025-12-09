function fillForm(a){
      document.getElementById('id').value          = a.id || '';
      document.getElementById('title').value       = a.title || '';
      document.getElementById('artist').value      = a.artist || '';
      document.getElementById('genre').value     = a.genre || '';
      document.getElementById('year').value        = a.year || '';
      document.getElementById('image').value       = a.image_url || '';
      document.getElementById('description').value = a.description || '';
      location.hash = '#addartwork';
      document.querySelector('.submit-btn').textContent = 'Update Artwork';
    }

