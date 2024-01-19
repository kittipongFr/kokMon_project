
<!-- //add -->
<style>
    /* Additional styles for the pop-up form */
    .form-popup {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      border: 3px solid #f1f1f1;
      z-index: 9;
      padding: 20px;
      background-color: #f1f1f1;
      max-width: 500px;
      width: 100%;
      max-height: 80vh; 
      overflow-y: auto;
      box-shadow: 0 3px 5px rgba(0, 0, 0, 0.5);
      .close-button {
        position: absolute;
        height: 40px; /* Adjust to your preferred height */
        width: 40px; /* Adjust to your preferred width */
        top: 0px;
        right: 10px;
        cursor: pointer;
    }
    }

</style>



<!-- form รับข้อมูล -->
<form id="rmtAddForm" class="form-popup">
<div class="close-button " >
<a class="btn btn-danger text-white" onclick="closeForm()" >
    X
</a> 
    </div>
    <input type="hidden" name="mem_id" id="mem_id" value="1"> <!-- Replace with your actual value -->
    
    <div class="form-group">
        <div class="col-sm-12 control-label">
            ชื่อร้าน:
        </div>
        <div class="col-sm-12">
            <input type="text" name="supply_name" id="supply_name" required class="form-control" minlength="2">
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12 control-label">
            ชื่อร้าน:
        </div>
        <div class="col-sm-12">
    <input type="date" id="date" name="date" class="form-control">
    </div>
    </div>

    <!-- Container for additional sections -->
    <div class="form-group" id="additionalSectionsContainer">
        <!-- Additional sections will be appended here -->
    </div>
    <div class="form-group">
    <div class=" d-flex float-end my-2">
            <button class="btn btn-primary mx-2" style="float: right;" type="button" id="addMt">เพิ่มรายการรับวัตถุดิบ <i class='fas fa-plus'></i></button>
    
        <!-- <a href="" onclick="closeForm()" class="btn btn-danger">ยกเลิก</a> -->
            <button class="btn btn-success " type="button"  id="submitBtn">บันทึกการรับวัตถุดิบ <i class="fa fa-save"></i></button>
           
        </div>
    </div>
</form>













<!-- เพิ่มรายการinput -->
<script>
var sectionCounter = 1;
var inputSets = []; // Array to store references to input sets

document.getElementById('addMt').addEventListener('click', function () {

    sectionCounter++;
    appendInputSet(sectionCounter);
});

function appendInputSet(counter) {
    var uniqueId = Date.now();

    var newInputContainer = document.createElement('div');
    newInputContainer.className = 'form-group input-set';
    newInputContainer.id = 'inputSet_' + uniqueId;

    var newLabel = document.createElement('div');
    newLabel.className = 'col-sm-12 control-label';
    newLabel.textContent = 'รายการรับที่ ' + counter + ':';

    var newInput = document.createElement('div');
    newInput.className = 'col-sm-12';

    // Append dropdown
    var dropdownId = appendDropdown('mt_id', 'ชื่อวัตถุดิบ(ห้ามเลือกวัตถุดิบซ้ำ!)', newInput);

    // Append input for amount
    var amountId = appendInput('amount', 'จำนวนรับ', newInput, counter);

    // Append input for price
    var priceId = appendInput('price', 'ราคา/หน่วย', newInput, counter);


    newInputContainer.appendChild(newLabel);
    newInputContainer.appendChild(newInput);


// Append delete button
var deleteBtn = document.createElement('button');
deleteBtn.className = 'btn btn-danger';
deleteBtn.style.marginTop = '10px'; // Adjust the margin-top value as needed
deleteBtn.type = 'button';

// Set the text content for the button
deleteBtn.appendChild(document.createTextNode('ยกเลิกรายการ'));
// Create the Font Awesome icon element
var deleteIcon = document.createElement('i');
deleteIcon.className = 'fas fa-trash'; // Replace with the appropriate Font Awesome class

// Append the icon to the delete button
deleteBtn.appendChild(deleteIcon);



deleteBtn.addEventListener('click', function () {
    var container = document.getElementById('additionalSectionsContainer');

    // Check if there's at least one input set before attempting to delete
    if (inputSets.length > 0) {
        var lastInputSet = inputSets.pop(); // Remove the last element from the array
        container.removeChild(lastInputSet.container);
        sectionCounter--; // Decrement counter when deleting a set
    }
});
newInput.appendChild(deleteBtn);


    newInputContainer.appendChild(newLabel);
    newInputContainer.appendChild(newInput);

    document.getElementById('additionalSectionsContainer').appendChild(newInputContainer);


    // Add the newInputContainer reference to the array
    inputSets.push({
        container: newInputContainer,
        dropdownId: dropdownId,
        amountId: amountId,
        priceId: priceId
    });
    console.log(inputSets);
}

function appendDropdown(fieldName, label, container) {
    var dropdownContainer = document.createElement('div');
    dropdownContainer.className = 'col-sm-12';

    var dropdownLabel = document.createElement('label');
    dropdownLabel.textContent = label + ':';

    var dropdown = document.createElement('select');
    var dropdownId = fieldName + Date.now(); // Generate a unique ID for the dropdown
    dropdown.name = fieldName;
    dropdown.id = dropdownId;
    dropdown.required = true;
    dropdown.className = 'form-control';

    // PHP loop (not executed in JavaScript)
    <?php foreach ($result as $results): ?>
        var option = document.createElement('option');
        option.value = "<?php echo $results['material_id']; ?>";
        option.textContent = "<?php echo $results['name'] . '(' . $results['unit'] . ')'; ?>";
        dropdown.appendChild(option);
    <?php endforeach; ?>

    dropdownContainer.appendChild(dropdownLabel);
    dropdownContainer.appendChild(dropdown);
    container.appendChild(dropdownContainer);
    return dropdownId;
}

function appendInput(fieldName, label, container, counter) {
    var inputContainer = document.createElement('div');
    inputContainer.className = 'col-sm-12';

    var inputLabel = document.createElement('label');
    inputLabel.textContent = label + ':';

    var input = document.createElement('input');
    input.type = 'number';
    var inputId = fieldName + counter + Date.now(); // Generate a unique ID for the input
    input.name = fieldName + counter; // Update the name with the counter
    input.id = inputId;   // Update the ID with the counter
    input.required = true;
    input.className = 'form-control';

    inputContainer.appendChild(inputLabel);
    inputContainer.appendChild(input);
    container.appendChild(inputContainer);

    return inputId;
}

        // Call appendInputSet function once when the page loads
        window.addEventListener('load', function () {
            // Initial call to create one input set
            appendInputSet(1);
        });
</script>


<script>
        document.getElementById("openFormBtn").addEventListener("click", function() {
      document.querySelector(".form-popup").style.display = "block";
    });

    function closeForm() {
        setTimeout(function () {
                        window.location.replace("http://127.0.0.1:8080/project/admin/receive_mt.php");
                    }, 100);
}



    </script>