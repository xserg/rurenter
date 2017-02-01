function doDelete()
{
	if (doDelete.arguments.length > 0) {
		txt = '¬ы уверены, что хотите очистить все данные?';
	} else {
		txt = '¬ы уверены, что хотите удалить эту запись?';
	}
	return confirm(txt);
}
