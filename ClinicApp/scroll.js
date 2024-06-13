window.addEventListener('beforeunload', function() {
    localStorage.setItem('scrollPositionPatients', JSON.stringify([window.scrollX, window.scrollY]));
  });
  

  window.addEventListener('load', function() {
    var scrollPosition = JSON.parse(localStorage.getItem('scrollPositionPatients'));
    if (scrollPosition !== null) {
      window.scrollTo(scrollPosition[0], scrollPosition[1]);
      localStorage.removeItem('scrollPositionPatients');
    }
  });

  function scrollToTheTop(){
    window.scrollTo(0, 0); 
  }
  