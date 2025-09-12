import { useUnit } from "effector-react"
import { componentRemoved } from "reused/Dialog/store"
import { $branch, allRightChanged, globalReset } from "store"

const Dialog = () => {
    const { id } = useUnit($branch)
    
    const onClick = () => {
        componentRemoved()
        allRightChanged(false)
        globalReset()
        window.location.replace('/branch/' + id)
    }

    return (
        <div className="text-center">
            <h3 className="text-xl">Поздравляем!</h3>
            <p>Ваше призведение успешно опубликовано</p>
            <button
                className="btn btn-primary dark:btn-info btn-wide mt-4"
                onClick={onClick}
            >
                Перейти к ветке
            </button>
        </div>
    )
}

export default Dialog
