type Props = {
  title?: string | number;
  children?: React.ReactNode;
}

const Wrapper: React.FC<Props> = ({ title, children }) => {
  return (
    <div className="flex flex-row justify-center">
      <div className="w-full md:w-2xl lg:w-4xl bg-base-100 p-4">
        {title ?
          <h1 className="text-2xl mt-2 mb-3">
            {title}
          </h1> : null}
        {children}
      </div>
    </div>
  )
}

export default Wrapper
