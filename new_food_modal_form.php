 <div class="modal-body">
    <div class="form-group">
        <label for="entered-food-name">Food Name:</label>
        <input type="text" id="entered-food-name" class="form-control" name="entered-food-name" value="<?php echo $_POST['name'] ?>" readonly>
    </div>
    <div class="form-group">
        <label for="cooked-status">Is it cooked?</label>
        <select id="cooked-status" name="cooked-status" class="form-control cooked-status">
            <option value="cooked">Cooked</option>
            <option value="notCooked">Not Cooked</option>
            <option value="NULL">N/A</option>
        </select>
    </div>
    <div class="form-group">
        <label for="calories">Calories per Serving:</label>
        <input type="number" id="calories" class="form-control" name="calories">
    </div>
    <div class="form-group">
        <label for="ideal_storage_temp">Ideal Storage Temperature (F):</label>
        <input type="number" id="ideal_storage_temp" class="form-control" name="ideal_storage_temp">
    </div>
    <div class="form-group">
        <label for="food_group">Food Group:</label>
        <select id="food_group" name="food_group" class="form-control food_group">
            <option value="fruits">Fruits</option>
            <option value="vegetable">Vegetables</option>
            <option value="protein">Protein</option>
            <option value="grains">Grains</option>
            <option value="dairy">Dairy</option>
            <option value="other">Other</option>
        </select>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary close_btn">Close</button>
        <input class="btn btn-primary" type="submit" name="addBtn" value="Add Food" title="Add Food" />
    </div>
 </div>