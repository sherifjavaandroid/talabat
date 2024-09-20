function select2exData() {

  return {
    loadResult() {
      // livewire.emit('logout');
    },
    //prepare the select 2
    init(id) {
      $("#" + id).select2({
        theme: "classic",
        width: "style"
      });
    },

  }
}
