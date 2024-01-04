document.addEventListener('DOMContentLoaded', function () {
  const allButton = document.querySelector("button.all");
  const generalButton = document.querySelector("button.general");
  const functionalitiesButton=document.querySelector("button.functionalities");
  const navigationButton=document.querySelector("button.navigation");
  const pricingButton = document.querySelector("button.pricing");
  const showButton = document.querySelector(".showinfo");
  const allFAQItems = document.querySelectorAll(".accordion-item");
  var optionHeader= document.querySelector(".option");
  
  showButton.addEventListener('click', function () {
    if (showButton.textContent == "Show all") {
        showButton.textContent = "Close all";
        allFAQItems.forEach((item) => {
            const collapseTarget = item.querySelector('.accordion-collapse');
            if (item.style.display === "block" && collapseTarget.classList.contains('show')) {
                return;
            }
            collapseTarget.classList.add('show');
        });
    } else {
        allFAQItems.forEach(item => {
            item.querySelector('.accordion-collapse').classList.remove('show');
        });
        showButton.textContent = "Show all";
    }
});


function resetpage() {

  showButton.textContent = "Show all";

  let isFirstItemFound = false;
  allFAQItems.forEach(item => {
      const collapseTarget = item.querySelector('.accordion-collapse');
      if (item.style.display === "block" && !isFirstItemFound) {
          if (!collapseTarget.classList.contains('show')) {
              collapseTarget.classList.add('show');
          }
          isFirstItemFound = true;
      } else {
          collapseTarget.classList.remove('show');
      }
  });
}


  allButton.addEventListener('click',function(){
    allFAQItems.forEach(item => {
      item.style.display ="block";
      optionHeader.textContent = "All";
    });
    resetpage();
  });

  generalButton.addEventListener('click', function() {
    allFAQItems.forEach(item => {
      const isItem = item.classList.contains("general");
      item.style.display = isItem ? "block" : "none";
      optionHeader.textContent = "General Information";
    });
    resetpage();
  });

  functionalitiesButton.addEventListener('click', function() {
    allFAQItems.forEach(item => {
      const isItem = item.classList.contains("functionalities");
      item.style.display = isItem ? "block" : "none";
      optionHeader.textContent = "Functionalities";
    });
    resetpage();
  });
  navigationButton.addEventListener('click', function() {
    allFAQItems.forEach(item => {
      const isItem = item.classList.contains("navigation");
      item.style.display = isItem ? "block" : "none";
      optionHeader.textContent = "Navigation";
    });
    resetpage();
  });

  pricingButton.addEventListener('click', function() {
    allFAQItems.forEach(item => {
      const isItem = item.classList.contains("pricing");
      item.style.display = isItem ? "block" : "none";
      optionHeader.textContent = "Pricing";
    });
    resetpage();
  });
});
