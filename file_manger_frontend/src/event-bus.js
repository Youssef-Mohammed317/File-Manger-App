import mitt from "mitt"

export const FileUploadStarted = "FileUploadStarted"

export const ShowErrorDialog = "ShowErrorDialog"

export const ShowDeleteFileConfirmation = "ShowDeleteFileConfirmation"

export const DeleteFilesCompleted = "DeleteFilesCompleted"

export const ReloadFiles = "ReloadFiles"

export const ShareFiles = "ShareFiles"

export const Notification = "Notification"

export const AddToMyFavorites = "AddToMyFavorites"

export const AddFavorite = "AddFavorite"

export const RemoveFavorites = "RemoveFavorites"

export const FocusSearchInput = "FocusSearchInput"

export const SearchInput = "SearchInput"

export const OpenFile = "OpenFile"

export const DownloadFiles = "DownloadFiles"

export const SmallScreen = "SmallScreen"

export const emitter = mitt()

export function showErrorDialogFn(message) {
    emitter.emit(ShowErrorDialog, message)
}

export function showDeleteFileConfirmationFn(data) {
    emitter.emit(ShowDeleteFileConfirmation, data)
}